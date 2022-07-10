<?php

namespace App\Command\Services\Scraper;

interface ScraperInterface
{
    public function runScrape();
    public function getProducts(): array;
}
