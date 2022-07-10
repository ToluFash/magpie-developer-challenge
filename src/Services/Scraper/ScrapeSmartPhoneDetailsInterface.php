<?php

namespace App\Services\Scraper;

interface ScrapeSmartPhoneDetailsInterface extends ScrapeProductDetailsInterface
{
    public function getCapacityMB(): int;
}
