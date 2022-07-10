<?php

namespace App\Entity;

use App\Services\Scraper\ScrapeProductDetailsInterface;

class Product implements \JsonSerializable
{
    protected ScrapeProductDetailsInterface $productDetails;

    public function __construct(ScrapeProductDetailsInterface $productDetails)
    {
        $this->productDetails = $productDetails;
    }
    public function getTitle(): string
    {
        return $this->productDetails->getTitle();
    }

    public function getPrice(): float
    {
        return $this->productDetails->getPrice();
    }

    public function getImageURL(): string
    {
        return $this->productDetails->getImageURL();
    }

    public function getAvailabilityText(): string
    {
        return $this->productDetails->getAvailabilityText();
    }

    public function isAvailable(): bool
    {
        return $this->productDetails->isAvailable();
    }

    public function getShippingText(): string
    {
        return $this->productDetails->getShippingText();
    }

    public function getShippingDate(): string
    {
        return $this->productDetails->getShippingDate();
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
