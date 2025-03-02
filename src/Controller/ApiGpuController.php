<?php

namespace App\Controller;

use App\Repository\InstanceSpotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/gpu', name: 'app_api_gpu')]
final class ApiGpuController extends AbstractController
{
    #[Route('/offers', name: 'offers', methods: 'GET')]
    public function getOffers(InstanceSpotRepository $repo): JsonResponse
    {

        $offers = $repo->findAll();
        $data = array_map(function ($offer) {
            return [
                'id' => $offer->getId(),
                'provider' => $offer->getInstanceDetail()->getProvider()->getName(),
                'instanceType' => $offer->getInstanceDetail()->getInstanceType(),
                'gpuModel' => $offer->getInstanceDetail()->getGpuModel(),
                'vram' => $offer->getInstanceDetail()->getVram(),
                'vcpu' => $offer->getInstanceDetail()->getVcpu(),
                'price' => $offer->getSpotPrice(),
                'availabilityZone' => $offer->getAvailabilityZone(),
                'os_supported' => $offer->getOsSupported(),
                'date' => $offer->getTimestamp(),
            ];
        }, $offers);
        return $this->json($data);
    }
}
