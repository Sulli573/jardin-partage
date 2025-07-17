<?php

namespace App\Controller;

use App\Service\MeteoService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class MeteoController extends AbstractController
{
    #[Route('/meteo', name: 'app_meteo')]
    public function meteo(MeteoService $meteoService, SerializerInterface $serializer, CacheInterface $cache): JsonResponse
    {
        //mettre en cache
        //Récupérer la "boîte" ($item).
        //Pour fonctionner, la function qui représente la boite et va créer un cache avec toutes les informations de la météo à besoin des variables $meteoService,$serializer donc : use()
        $meteo = $cache->get("meteo_cache_data", function(ItemInterface $item) use($meteoService,$serializer){
            $item->expiresAfter(43200);
            $meteo = $meteoService->invoke();
            //convertir l'objet meteo en json
            $meteo = $serializer->serialize($meteo, "json");
            return $meteo;
        });
        

        return new JsonResponse($meteo,json:true);
    }
}
