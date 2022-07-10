<?php

namespace App\Services\Scraper;

use Symfony\Component\DomCrawler\Crawler;

interface ScrapeFetcherInterface
{
    public function fetchDocument(string $url): Crawler;

}
