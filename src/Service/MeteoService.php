<?php 

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MeteoService
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function fetchInformations(): array
    {
        $response = $this->client->request(
            'GET',
            'http://www.infoclimat.fr/public-api/gfs/json?_ll=48.85341,2.3488&_auth=U0leSQ9xU3FUeQQzVSMCKwBoBTAKfAYhAHwGZQ5rVyoCaV4%2FBGRdO14wVCkFKlVjAy4HZFliAzMEbwN7WylfPlM5XjIPZFM0VDsEYVV6AikALgVkCioGIQBgBmYOfVc1AmZePgR5XT1eMlQzBStVYAM0B25ZeQMkBGYDY1syXzVTNF49D2RTMFQ7BG5VegIpADUFbQoxBjgANgYwDjdXZwIzXm4ENl07XmJUNQUrVWYDOAdvWWUDPgRiA2FbP18jUy9eQw8fUyxUewQkVTACcAAuBTAKawZq&_c=807b268e6cef706f8d6b6efcefbabd85'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
    
        //supprimer les 4 premiers éléménts du tableaux (ils ne sont pas utile)
        $cut = array_splice($content,5,count($content));
        //Je veux Regrouper par jours et heure les données:

        //Créer un tableau vide.
        //Foreach sur tableau initial, 
        //Dans la clé je dois récupérer la date et l'heure
        //Dans mon tableau vide je dois créer la clé "date"
        //Ajouter les données de l'heure à la date (clé  heure) ayant pour valeur les données 

        $meteoParJour = [];
        foreach ($cut as $key=>$value) {
            $date = substr($key,0,10);
            $heure = substr($key,11,5);
            //Si la clé date n'existe pas dans le tableau $meteoParJour, la créé
            if (!isset($meteoParJour[$date])) {
                $meteoParJour[$date] = [];        
            }
            //Création de la clé heure dans la clé date du tableau $meteoParJour, et assigne value 
            $meteoParJour[$date][$heure] = $value;

            //Envoyer un mail à chaque utilisateur si la temparature est supérieur à un certain degré:
            
        //     if ($value["temperature"]["2m"] > 25) {
        //         $users = $UserRepository=>findAll();
        //         foreach($user as $users) {
        //         $emailService=>sendEmail($user=>getEmail(),"Température élévée le " . $date . "à" . $heure);
        //     } 
        // }
        }
       
        return $meteoParJour;
    }
}

?>