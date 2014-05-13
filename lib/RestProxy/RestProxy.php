<?php
namespace RestProxy;

use Guzzle\Http\Client;
use Symfony\Component\HttpFoundation\Response;

class RestProxy
{
    private $request;
    private $map;
    private $response;

    private $allowedHeaders = array();

    public function __construct(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request  = $request;
    }

    public function register($name, $url)
    {
        $this->map[$name] = $url;
    }

    public function setAllowedHeaders(array $headers)
    {
        $this->allowedHeaders = $headers;
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
            $url .= "?{$queryString}";

            $headers = array();
            foreach($this->allowedHeaders as $headerName) {
                if($this->request->headers->has($headerName)) {
                    $headers[$headerName] = $this->request->headers->get($headerName);
                }
            }

            switch ($this->request->getMethod()) {
                case 'GET':
                    $guzzle = $client->get($url, $headers);
                    break;
                case 'POST':
                    $guzzle = $client->post($url, $headers, $this->request->request->all());
                    break;
                case 'DELETE':
                    $guzzle = $client->delete($url, $headers);
                    break;
                case 'PUT':
                    $guzzle = $client->put($url, $headers, $this->request->request->all());
                    break;
            }

            $response = $guzzle->send();
            $this->response = new Response($response->getBody(), $response->getStatusCode(), $response->getHeaders()->toArray());
        }
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
