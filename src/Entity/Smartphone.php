<?php

namespace App\Entity;

use App\Services\Scraper\ScrapeSmartPhoneDetailsInterface;

class Smartphone extends Product
{

    public function __construct(ScrapeSmartPhoneDetailsInterface $phoneDetails)
    {
        parent::__construct($phoneDetails);
    }

    /**
     * Casts to ScrapeSmartPhoneDetailsInterface and collects capacity.
     *
     * @return int
     */
    public function getCapacityMB(): int
    {
        /** @var ScrapeSmartPhoneDetailsInterface $smartphone */
        $smartphone = $this->productDetails;
        return $this->productDetails->getCapacityMB();
    }

    private function getColour()
    {
        /** @var ScrapeSmartPhoneDetailsInterface $smartphone */
        $smartphone = $this->productDetails;
        return $this->productDetails->getColour();
    }

    public function jsonSerialize(): array
    {
        return array(
            'title' => $this->getTitle(),
            'price' => $this->getPrice(),
            'imageUrl' => $this->getImageURL(),
            'capacityMB' => $this->getCapacityMB(),
            'colour' => $this->getColour(),
            'availabilityText' => $this->getAvailabilityText(),
            'isAvailable' => $this->isAvailable(),
            'shippingText' => $this->getShippingText(),
            'shippingDate' => $this->getShippingDate(),
        );
    }

}
