<?php

namespace App\Command\Services\Scraper;

interface ScrapeSmartPhoneDetailsInterface extends ScrapeProductDetailsInterface
{
    public function getCapacityMB(): int;
}
