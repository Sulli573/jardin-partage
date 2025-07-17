<?php

namespace App\Service;

use App\Entity\Meteo;
use App\Entity\MeteoDay;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MeteoService
{
    private int $nbOfDays = 3;
    private string $token = "U0leSQ9xU3FUeQQzVSMCKwBoBTAKfAYhAHwGZQ5rVyoCaV4%2FBGRdO14wVCkFKlVjAy4HZFliAzMEbwN7WylfPlM5XjIPZFM0VDsEYVV6AikALgVkCioGIQBgBmYOfVc1AmZePgR5XT1eMlQzBStVYAM0B25ZeQMkBGYDY1syXzVTNF49D2RTMFQ7BG5VegIpADUFbQoxBjgANgYwDjdXZwIzXm4ENl07XmJUNQUrVWYDOAdvWWUDPgRiA2FbP18jUy9eQw8fUyxUewQkVTACcAAuBTAKawZq&_c=807b268e6cef706f8d6b6efcefbabd85";
    private string $longitude = "48.85341";
    private string $latitude = "2.3488&";

    public function __construct(
        private HttpClientInterface $client,
    ) {}

    public function invoke(): Meteo {
        $data = $this->fetchInformations();
        $daysWithHours = $this->getHoursOfOneDay($data);

        $meteo = new Meteo();
        //array_slice pour récupérer une portion de tableau de 0 à 3 pour récupérer que 3 jours mettre la variable $nbOfDays dans le slice
        $daysWithHours = array_slice($daysWithHours, 0, $this->nbOfDays);
        foreach ($daysWithHours as $day => $hours) {
            $meteoDay = new MeteoDay();
            $meteoDay->setDay(new \DateTime($day));
            $meteoDay->setTemperatureMax($this->getTempMax($hours));
            $meteoDay->setTemperatureMin($this->getTempMin($hours));
            $meteoDay->setPrecipitation($this->getAveragePrecipitation($hours));

            $meteo->addDay($meteoDay);
        }
        return $meteo;
    }

    /**
     * @param array $dataHours représente un tableau des heures de la journée.
     * @return array retourne un tableau des températures de la journée.
     */
    private function getAllTempOnDay(array $dataHours): array
    {
        $allTempOfTheDay = [];
        //Boucle sur chaque heure de la journée.
        foreach ($dataHours as $hour) {
            //va mettre toutes les températures du jour dans le tableau $allTempOfTheDay
            $allTempOfTheDay[] = $hour['temperature']['2m'];
        }
        return $allTempOfTheDay;
    }

    public function getTempMax(array $dataHours): float
    {
        $temperaturesOfTheDay = $this->getAllTempOnDay($dataHours); // récupére un tableau avec toutes les témpératures de la journée.

        $tempMaxKelvin = max($temperaturesOfTheDay);
        $tempMaxCelsius = $this->convertKelvinToCelsius($tempMaxKelvin);
        return round($tempMaxCelsius,2); //arrondi à 2 chiffres après la virgule

    }

    public function getTempMin(array $dataHours): float
    {
        $temperaturesOfTheDay = $this->getAllTempOnDay($dataHours); 

        $tempMinKelvin = min($temperaturesOfTheDay);
        $tempMinCelsius = $this->convertKelvinToCelsius($tempMinKelvin);
        return round($tempMinCelsius,2); //arrondi à 2 chiffres après la virgule
    }


    /**
     * @param array $dataHours représente un tableau des heures de la journée.
     * @return array retourne un tableau des précipitations de la journée.
     */
    private function getAllPrecipitationOnDay(array $dataHours) : array
    {
        $allPrecipitationOfTheDay = [];

        foreach ($dataHours as $hour) {
            $allPrecipitationOfTheDay[] = $hour['pluie'];
        }
        return $allPrecipitationOfTheDay;
    }

    public function getAveragePrecipitation(array $dataHours) : float
    {
        $precipitationOftheDay = $this->getAllPrecipitationOnDay($dataHours);
        $average = array_sum($precipitationOftheDay) / count($precipitationOftheDay);
        return round($average,4);
    }

    //Récupération de toutes les heures d'une journée
    public function getHoursOfOneDay(array $dataDays): array
    {
        //trier par jour (le 11/07, le 12/07 etc...)
              //Créer un tableau vide.
        //Foreach sur tableau initial, 
        //Dans la clé je dois récupérer la date et l'heure
        //Dans mon tableau vide je dois créer la clé "date"
        //Ajouter les données de l'heure à la date (clé  heure) ayant pour valeur les données 

        $meteoParJour = [];
        foreach ($dataDays as $key => $value) {
            $dateStr = substr($key, 0, 10);
            $heure = substr($key, 11, 5);
            
            // Dans $meteoParJour on a le jour avec toutes les heures et les valeurs (temperatures, precipitation) puis on passe au jour suivant
            //Comme dans un panier où on a $panier['boucherie']['poulet'] = 'prix: 2€'
            //$panier['fromagerie']['tomme'] = 'prix: 5€'

            $meteoParJour[$dateStr][$heure] = $value;  
        }
        return $meteoParJour;
    }
    
    
//call l'api pour récupérer les informations
    public function fetchInformations(): array
    {
        $response = $this->client->request(
            'GET',
            'http://www.infoclimat.fr/public-api/gfs/json?_ll=' . $this->longitude . ',' . $this->latitude . '&_auth=' . $this->token
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0]; // on recupère le format retourner par le page (peut etre html, json,xml etc... ici c'est du json)
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray(); //transforme le json en tableau.
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        //supprimer les 4 premiers éléménts du tableaux (ils ne sont pas utiles)
        $data = array_splice($content, 5, count($content));

        return $data;
    }
    public function convertKelvinToCelsius(float $tempKelvin): float
    {
        return $tempKelvin - 273.15; 
    }
}
