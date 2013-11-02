<?php
namespace RestProxy;

use Guzzle\Http\Client;

class RestProxy
{
    private $request;
    private $curl;
    private $map;

    private $content;
    private $headers;

    public function __construct(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request  = $request;
    }

    public function register($name, $url)
    {
        $this->map[$name] = $url;
    }

    public function run()
    {
        foreach ($this->map as $name => $mapUrl) {
            $this->dispatch($name, $mapUrl);
        }
    }

    private function dispatch($name, $mapUrl)
    {
        $url = $this->request->getPathInfo();

        $client = new Client($mapUrl);

        if (strpos($url, $name) == 1) {
            $url         = $mapUrl . str_replace("/{$name}", NULL, $url);
            $queryString = $this->request->getQueryString(); // ??

            switch ($this->request->getMethod()) {
                case 'GET':
                    $guzzle = $client->get($url);
                    break;
                case 'POST':
                    $guzzle = $client->post($url);
                    break;
                case 'DELETE':
                    $guzzle = $client->delete($url);
                    break;
                case 'PUT':
                    $guzzle = $client->put($url);
                    break;
            }

            $response = $guzzle->send();
            $this->content = $response->getBody();
        }
    }

    public function getContent()
    {
        return $this->content;
    }
}
