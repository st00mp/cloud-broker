<?php

namespace App\Command;

use App\Entity\Provider;
use App\Entity\InstanceDetail;
use App\Entity\InstanceSpot;
use Doctrine\ORM\EntityManagerInterface;
use Aws\Ec2\Ec2Client;
use Aws\Exception\AwsException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fetch-spot-offers',
    description: 'Récupère les offres Spot AWS (via le SDK) et les stocke dans Entity InstanceSpot/InstanceDetail.'
)]
class FetchSpotOffersCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 1) Credentials
        $key    = $_ENV['AWS_ACCESS_KEY_ID'] ?? null;
        $secret = $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null;
        $region = $_ENV['AWS_REGION'] ?? 'eu-central-1';

        if (!$key || !$secret) {
            $output->writeln("<error>Clés AWS manquantes ! Mets-les dans .env.local</error>");
            return Command::FAILURE;
        }

        // 2) Client EC2
        $ec2Client = new Ec2Client([
            'region'      => $region,
            'version'     => 'latest',
            'credentials' => [
                'key'    => $key,
                'secret' => $secret,
            ],
        ]);

        // 3) describeSpotPriceHistory
        try {
            $result = $ec2Client->describeSpotPriceHistory([
                'ProductDescriptions' => ['Linux/UNIX'],
                'MaxResults'          => 99,
            ]);
            $spotPrices = $result->get('SpotPriceHistory') ?? [];
        } catch (AwsException $e) {
            $output->writeln("<error>Erreur describeSpotPriceHistory : {$e->getAwsErrorMessage()}</error>");
            return Command::FAILURE;
        }

        if (empty($spotPrices)) {
            $output->writeln("<comment>Aucune offre Spot trouvée.</comment>");
            return Command::SUCCESS;
        }

        // 4) Provider (AWS)
        $providerRepo = $this->em->getRepository(Provider::class);
        $awsProvider  = $providerRepo->findOneBy(['name' => 'AWS']);
        if (!$awsProvider) {
            $awsProvider = new Provider();
            $awsProvider->setName('AWS');
            $this->em->persist($awsProvider);
            $this->em->flush();
        }

        // Compteurs
        $countInserted = 0;
        $countSkipped  = 0;

        // 5) Pour chaque Spot
        foreach ($spotPrices as $offer) {
            $instanceType     = $offer['InstanceType'];
            $spotPrice        = (float) $offer['SpotPrice'];
            $availabilityZone = $offer['AvailabilityZone'];

            // 5a) describeInstanceTypes
            try {
                $details = $ec2Client->describeInstanceTypes([
                    'InstanceTypes' => [$instanceType],
                ]);
                $items = $details->get('InstanceTypes') ?? [];
                if (empty($items)) {
                    // On pourrait incrémenter un autre compteur "not found"
                    $countSkipped++;
                    continue;
                }

                $item     = $items[0];
                $gpuInfo  = $item['GpuInfo']['Gpus'][0] ?? null;
                $gpuModel = $gpuInfo['Name'] ?? 'none';
                $vram     = $gpuInfo['MemorySizeInMiB'] ?? 0;
                $vcpu     = $item['VCpuInfo']['DefaultVCpus'] ?? 0;
                $ram      = $item['MemoryInfo']['SizeInMiB'] ?? 0;
                $network  = $item['NetworkInfo']['NetworkPerformance'] ?? '';
            } catch (AwsException $e) {
                // On skippe, on incrémente le compteur "skipped"
                $countSkipped++;
                continue;
            }

            // Filtre : si pas de GPU (vram=0) => skip
            if ($vram <= 0) {
                $countSkipped++;
                continue;
            }

            // 6) InstanceDetail
            $instanceDetailRepo = $this->em->getRepository(InstanceDetail::class);
            $instanceDetail = $instanceDetailRepo->findOneBy(['instanceType' => $instanceType]);

            if (!$instanceDetail) {
                $instanceDetail = new InstanceDetail();
                $instanceDetail->setInstanceType($instanceType);
                $instanceDetail->setProvider($awsProvider);
                $instanceDetail->setGpuModel($gpuModel);
                $instanceDetail->setVram($vram);
                $instanceDetail->setVcpu($vcpu);
                $instanceDetail->setRam($ram);
                $instanceDetail->setNetworkPerformance($network);
                $instanceDetail->setOsSupported('Linux/UNIX');

                $this->em->persist($instanceDetail);
                $this->em->flush();
            }
            // else { ... mise à jour éventuelle }

            // 7) InstanceSpot
            $spotEntity = new InstanceSpot();
            $spotEntity->setInstanceDetail($instanceDetail);
            $spotEntity->setSpotPrice($spotPrice);
            $spotEntity->setAvailabilityZone($availabilityZone);
            $spotEntity->setTimestamp(new \DateTime());

            $this->em->persist($spotEntity);
            $this->em->flush();

            $countInserted++;
        }

        // Au final, on affiche un résumé
        $output->writeln("<info>$countInserted offres Spot insérées, $countSkipped ignorées.</info>");
        return Command::SUCCESS;
    }
}
