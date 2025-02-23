<?php

namespace App\Command;

use App\Entity\InstanceDetail;
use App\Entity\Provider;
use Aws\Ec2\Ec2Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-gpu-types',
    description: "Récupère la liste des types d'instances AWS qui possèdent un ou plusieurs GPU et mets à jour InstanceDetails."
)]

class UpdateGpuTypesCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 1) Récupération des crédentials (via .env.local par ex.)
        $key        = $_ENV['AWS_ACCESS_KEY_ID'] ?? null;
        $secret     = $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null;
        $region     = $_ENV['AWS_REGION'] ?? 'eu-central-1';

        if (!$key || !$secret) {
            $output->writeln("<error>Clés AWS manquantes ! Ajoutez-les dans .env.local</error>");
            return Command::FAILURE;
        }

        // 2) Création du client EC2
        $ec2Client = new Ec2Client([
            'region'        => $region,
            'version'       => 'latest',
            'credentials'   => [
                'key'    => $key,
                'secret' => $secret,
            ],
        ]);

        // 3) Utiliser un paginator DescribeInstanceTypes filtré sur GPU
        try {
            $paginator = $ec2Client->getPaginator('DescribeInstanceTypes', [
                'Filters' => [
                    [
                        'Name'   => 'current-generation',
                        'Values' => ['true'],
                    ],
                    [
                        'Name'   => 'bare-metal',
                        'Values' => ['false'],
                    ],
                ],
                'MaxResults' => 100, // nombre maximum par page
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            $output->writeln("<error>Erreur describeInstanceTypes : {$e->getAwsErrorMessage()}</error>");
            return Command::FAILURE;
        }

        // 4) Parcours des pages
        $countNew       = 0;
        $countUpdated   = 0;
        $pageCount      = 0;

        $maxPages  = PHP_INT_MAX; // par défaut, pas de limite (prod)

        if ($_ENV['APP_ENV'] === 'dev') {
            $maxPages = 60;
        }

        // Nom du provider (AWS)
        $providerRepo = $this->em->getRepository(Provider::class);
        $awsProvider = $providerRepo->findOneBy(['name' => 'AWS']);
        if (!$awsProvider) {
            $awsProvider = new Provider();
            $awsProvider->setName('AWS');
            $this->em->persist($awsProvider); // Persist explicitement
            $this->em->flush();
        }

        $providerId = $awsProvider->getId(); // Stocker l'ID avant la boucle

        foreach ($paginator as $page) {
            $pageCount++;
            $items = $page->get('InstanceTypes') ?? [];

            // Utiliser la référence directe après chaque clear()
            $awsProvider = $this->em->getReference(Provider::class, $providerId);

            // 5) Pour chaque instance GPU
            foreach ($items as $awsItem) {

                // --- Vérifie si GPU ? ---
                $gpuInfoArray = $awsItem['GpuInfo']['Gpus'] ?? [];
                if (empty($gpuInfoArray)) {
                    // => Pas de GPU, on skip
                    continue;
                }

                // Nom de l'instance (ex: "p3.2xlarge")
                $instanceType   = $awsItem['InstanceType'];

                // Extraire les infos importantes
                $vcpu           = $awsItem['VCpuInfo']['DefaultVCpus'] ?? 0;
                $ramMiB         = $awsItem['MemoryInfo']['SizeInMiB'] ?? 0;
                $network        = $awsItem['NetworkInfo']['NetworkPerformance'] ?? 'Unknown';

                // Récupérer les infos GPU (on suppose 1 GPU, ou on additionne)
                $gpuModel       = 'Unknown';
                $vramMiB        = 0;
                $totalGpus      = 0;

                // Option: Calculer la somme si plusieurs GPUs
                $vramMiB = 0;
                $gpuModels = [];  // si tu veux stocker ou tracer plusieurs modèles

                foreach ($gpuInfoArray as $gpuUnit) {
                    $countPerUnit   = $gpuUnit['Count'] ?? 0;
                    $memoryPerUnit  = $gpuUnit['MemoryInfo']['SizeInMiB'] ?? 0;
                    $modelName      = $gpuUnit['Name'] ?? 'Unknown';

                    // Additionne le total de VRAM
                    // ex: 2 x GPU V100 => 2 * 16384 = 32768 MiB
                    $vramMiB += ($countPerUnit * $memoryPerUnit);

                    // Optionnel: collecter les noms
                    // (si ta table n'a qu'un seul champ 'gpuModel', tu décides quoi en faire)
                    for ($i = 0; $i < $countPerUnit; $i++) {
                        $gpuModels[] = $modelName;
                    }

                    $totalGpus += $countPerUnit;
                }

                // Stocker "gpuModel" si tu n'en as qu'un ? S'il y en a plusieurs (cas multi-GPU), tu peux :
                // - Soit mettre le premier,
                // - Soit une concat "V100x2 + T4x2"
                // - Soit un champ "gpuModels" en JSON dans ta BDD
                // Exemple simple : on stocke le dernier 'modelName' 
                $gpuModel = implode(',', array_unique($gpuModels));
                if (!$gpuModel) {
                    $gpuModel = 'Unknown'; // fallback
                }

                // On va insérer / mettre à jour InstanceDetail
                // Vérifier si on l'a déjà en base
                $existingDetail = $this->em->getRepository(InstanceDetail::class)
                    ->findOneBy(['instanceType' => $instanceType]);

                // Nouvelle instanceDetail ? 
                if (!$existingDetail) {
                    $instanceDetail = new InstanceDetail();
                    $instanceDetail->setInstanceType($instanceType);
                    $countNew++;
                } else {
                    $instanceDetail = $existingDetail;
                    $countUpdated++;
                }

                // Mettre à jour les champs
                $instanceDetail->setProvider($awsProvider);
                $instanceDetail->setGpuModel($gpuModel);
                $instanceDetail->setVram($vramMiB);
                $instanceDetail->setVcpu($vcpu);
                $instanceDetail->setRam($ramMiB);
                $instanceDetail->setNetworkPerformance($network);
                $instanceDetail->setNumberOfGpus($totalGpus); // Ce champ "numberOfGpus" est fictif, à ajouter si besoin
                $instanceDetail->setHasGpu($totalGpus > 0); // Si le nombre total de GPU > 0, on met à true, sinon false.

                // Persister
                $this->em->persist($instanceDetail);
            }

            // 6) Flush intermédiaire (batch)
            $this->em->flush();
            $this->em->clear();
            // Attention: après un clear, les entités détachées ne sont plus managées
            // Ça peut poser problème si tu réutilises $instanceDetail plus tard.
            // Mais dans ce cas, ça va car on est dans la boucle, et on recrée un findOneBy() au passage suivant.

            // Après avoir traité la page
            if ($pageCount >= $maxPages) {
                $output->writeln("<comment>Mode dev: on arrête après $pageCount pages</comment>");
                break;
            }
        }

        // Flush final
        $this->em->flush();
        $this->em->clear();

        $output->writeln("<info>$countNew Nouveaux types GPU créés, $countUpdated mis à jour.</info>");

        return Command::SUCCESS;
    }
}
