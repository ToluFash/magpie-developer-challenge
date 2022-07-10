<?php

namespace App\Services\Scraper;

interface ScraperInterface
{
    public function runScrape();
    public function getProducts(): array;
}
