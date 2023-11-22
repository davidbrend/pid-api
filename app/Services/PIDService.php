<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Nette\Utils\Json;
use Psr\Http\Message\ResponseInterface;

class PIDService
{
    private const DATA_PID_URL = 'http://data.pid.cz/pointsOfSale/json/';

    /**
     * @throws GuzzleException
     * @throws \Exception
     * @return array<mixed>
     */
    public function getPointsOfSaleJson(): array
    {
        $body = $this->execute(self::DATA_PID_URL . 'pointsOfSale.json')->getBody();
        return Json::decode($body, Json::FORCE_ARRAY);
    }

    /**
     * @throws GuzzleException
     */
    private function execute(string $url): ResponseInterface
    {
        $client = new Client([
            'base_uri' => $url,
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET');
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() <= 300) {
            try {
                return Json::decode($response->getBody(), Json::FORCE_ARRAY);
            } catch (\Throwable $e) {
                throw new \Exception($e->getMessage());
            }
        }
        throw new \Exception('Invalid Response');
    }

    /**
     * @throws GuzzleException
     * @throws \Exception
     * @return array<mixed>
     */
    public function getConstsJson(): array
    {
        $body = $this->execute(self::DATA_PID_URL . 'consts-cs.json')->getBody();
        return Json::decode($body, Json::FORCE_ARRAY);
    }
}