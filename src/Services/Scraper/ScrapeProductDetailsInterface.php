<?php

namespace App\Services\Scraper;

interface ScrapeProductDetailsInterface
{
    public function getTitle(): string;
    public function getPrice(): float;
    public function getImageURL(): string;
    public function getColour(): string;
    public function getAvailabilityText(): string;
    public function isAvailable(): bool;
    public function getShippingText(): string;
    public function getShippingDate(): string;

}
