<?php

namespace App\Controller;

use App\Service\MeteoService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MeteoController extends AbstractController
{
    #[Route('/meteo', name: 'app_meteo')]
    public function meteo(MeteoService $meteoService, SerializerInterface $serializer): JsonResponse
    {
        $meteo = $meteoService->invoke();
        //convertir l'objet meteo en json
        $meteo = $serializer->serialize($meteo, "json");

        return new JsonResponse($meteo,json:true);
    }
}
