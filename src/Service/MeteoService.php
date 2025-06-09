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

        dd($content);
    
        return $content;
    }
}

?>