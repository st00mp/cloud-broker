<?php

namespace App\Command;

use Aws\Ec2\Ec2Client;
use Aws\Exception\AwsException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;

use App\Entity\InstanceDetail;
use App\Entity\InstanceSpot;

#[AsCommand(
    name: 'app:fetch-spot-offers',
    description: 'Récupère uniquement les prix Spot pour les instances GPU déjà enregistrées dans InstanceDetail.'
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
            $output->writeln("<error>Clés AWS manquantes ! Ajoutez-les dans .env.local</error>");
            return Command::FAILURE;
        }

        // 2) Client EC2
        $ec2Client = new Ec2Client([
            'region'        => $region,
            'version'       => 'latest',
            'credentials'   => [
                'key'       => $key,
                'secret'    => $secret,
            ],
        ]);

        // 3) Récupérer la liste des InstanceDetail GPU
        $detailsRepo = $this->em->getRepository(InstanceDetail::class);
        $gpuDetails  = $detailsRepo->findBy(['hasGpu' => true]);

        if (empty($gpuDetails)) {
            $output->writeln("<comment>Aucun InstanceDetail GPU trouvé en base.</comment>");
            return Command::SUCCESS;
        }

        // Créer un tableau de string : ex ["p3.2xlarge", "g4dn.xlarge", ...]
        $instanceTypes = array_map(fn(InstanceDetail $d) => $d->getInstanceType(), $gpuDetails);

        // Supprimer les doublons au cas où
        $instanceTypes = array_unique($instanceTypes);

        // 4) Chunk si besoin (pour éviter de dépasser la limite AWS
        $chunks = array_chunk($instanceTypes, 20);

        $countInserted = 0;
        $countSkipped = 0;
        $maxResults = 1000; // Limite globale à 1000 résultats

        // 5) Pour chaque chunk, appel DescribeSpotPriceHistory
        foreach ($chunks as $chunk) {
            try {
                $paginator = $ec2Client->getPaginator('DescribeSpotPriceHistory', [
                    'ProductDescriptions' => ['Linux/UNIX'],
                    'Filters' => [
                        [
                            'Name'  => 'instance-type',
                            'Values' => $chunk,
                        ],
                    ],
                    // MaxResults limite de nombre de résultats par page
                    'MaxResults' => 99,
                ]);

                foreach ($paginator as $page) {
                    $spotPrices = $page->get('SpotPriceHistory') ?? [];

                    // 6) Insérer en DB
                    foreach ($spotPrices as $offer) {
                        if ($countInserted >= $maxResults) {
                            //  Arrête la récupération dès que la limite est atteinte
                            break 3; // Quitte la boucle foreach et foreach parent
                        }

                        $instanceType       = $offer['InstanceType'];
                        $spotPrice          = (float) $offer['SpotPrice'];
                        $productDesc        = $offer['ProductDescription'];
                        $availabilityZone   = $offer['AvailabilityZone'];
                        $timestamp          = new DateTime($offer['Timestamp']); // AWS renvoie un string datettime. Tu peux new DateTime() dessus

                        // Retrouver l'InstanceDetail
                        $detail = $this->em->getRepository(InstanceDetail::class)
                            ->findOneBy(['instanceType' => $instanceType]);

                        if (!$detail) {
                            $countSkipped++;
                            continue;
                        }

                        $spotEntity = new InstanceSpot();
                        $spotEntity->setInstanceDetail($detail);
                        $spotEntity->setSpotPrice($spotPrice);
                        $spotEntity->setAvailabilityZone($availabilityZone);
                        $spotEntity->setTimestamp($timestamp);

                        // Stocker l'OS dans la table spot
                        $spotEntity->setOsSupported($productDesc);

                        $this->em->persist($spotEntity);
                        $countInserted++;
                    }

                    // Flush de temps en temps
                    if ($countInserted % 100 === 0) {
                        $this->em->flush();
                        $this->em->clear();
                        gc_collect_cycles();  // Libère la mémoire PHP (garbage collector)
                    }
                }
            } catch (AwsException $e) {
                $output->writeln("<error>Erreur describeSpotPriceHistory : {$e->getAwsErrorMessage()}</error>");
                return Command::FAILURE;
            } catch (\Exception $e) {
                $output->writeln("<error>Erreur inattendue : {$e->getMessage()}</error>");
                return Command::FAILURE;
            }
        }

        // Flush final
        $this->em->flush();
        $this->em->clear();

        $output->writeln("<info>$countInserted prix Spot insérés, $countSkipped ignorés.</info>");

        return Command::SUCCESS;
    }
}
