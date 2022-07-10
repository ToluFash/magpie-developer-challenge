<?php

namespace App\Command\Services\Scraper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeHelper implements ScrapeFetcherInterface
{


    private Client $client;
    private LoggerInterface $logger;

    /**
     * @param Client $client
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function fetchDocument(string $url): Crawler
    {
        $content = "";
        try {
            $this->logger->info("Fetching Document from $url started!");
            $response = $this->client->get($url);
            $content = $response->getBody()->getContents();
            $this->logger->info("Fetching Document from $url completed with status {$response->getStatusCode()}");
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage());
        }
        catch (\Exception $e){
            print_r($e->getMessage());
        } finally {
            return new Crawler($content, $url);
        }
    }
}
