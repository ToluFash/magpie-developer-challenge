<?php

namespace App\Services\Scraper;

use DateTimeImmutable;
use NumberFormatter;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeSmartPhoneDetails implements ScrapeSmartPhoneDetailsInterface
{

    private Crawler $crawler;
    private int $colourNodeEq;
    const SIZE_CONVERSION_MB = array(
        'B' =>1E-6,
        'KB' => 1E-3,
        'MB' => 1E0,
        'GB' => 1E3,
        'TB' => 1E6
    );
    const DATE_FORMATS_PATTERN = array(
        'jS F Y' => '/([1-9]|[1-2][0-9]|3[0-1])(st|th|rd)\s\w+\s\d{4}/',
        'd F Y' => '/([1-9]|0[1-9]|[1-2][0-9]|3[0-1])\s\w+\s\d{4}/',
        'Y-m-d' => '/[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/',
        'Y-d-m' => '/[0-9]{4}-(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])/',
        'm-d-Y' => '/(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-[0-9]{4}/',
        'd-m-Y' => '/(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}/',
        'Y/m/d' => '/[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])/',
        'Y/d/m' => '/[0-9]{4}\/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])/',
        'm/d/Y' => '/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}/',
        'd/m/Y' => '/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}/'
    );
    const SIZE_PATTERN = "/(B|KB|MB|GB|TB)/";

    public function __construct(Crawler $crawler, int $colourNodeEq)
    {
        $this->crawler = $crawler;
        $this->colourNodeEq = $colourNodeEq;
    }

    public function getTitle(): string
    {
        return "{$this->crawler->filter('span.product-name')->text()} {$this->crawler->filter('span.product-capacity')->text()}";
    }

    public function getPrice(): float
    {
        return (new NumberFormatter('en_GB', NumberFormatter::CURRENCY))->parseCurrency(
            $this->crawler->filter('div.text-center')->eq(0)->text(), $curr
        );
    }

    public function getImageURL(): string
    {
        return $this->crawler->filter('img')->image()->getUri();
    }

    public function getColour(): string
    {
        return $this->crawler->filter('div.px-2 > span')->eq($this->colourNodeEq)->attr('data-colour');
    }

    public function getCapacityMB(): int
    {
        return $this->resolveCapacity($this->crawler->filter('span.product-capacity')->text());
    }

    public function getAvailabilityText(): string
    {
        return substr($this->crawler->filter('div.text-center')->eq(1)->text(), 14);
    }

    public function isAvailable(): bool
    {
        return strpos($this->getAvailabilityText(), 'In Stock') !== false;
    }

    public function getShippingText(): string
    {
        return $this->crawler->filter('div.text-center')->count() > 2
            ? $this->crawler->filter('div.text-center')->eq(2)->text() :
            "";
    }

    public function getShippingDate(): string
    {
        return $this->resolveDate($this->getShippingText());
    }

    private function resolveCapacity(string $capacity): string
    {
        $size = (int) $capacity;
        preg_match(self::SIZE_PATTERN, $capacity, $matches);
        $unit = $matches[0];
        return round($size * self::SIZE_CONVERSION_MB[$unit]);
    }

    private function resolveDate(string $dateString): string
    {
        if (!$dateString) {
            return "";
        }
        foreach (self::DATE_FORMATS_PATTERN as $format => $regExp) {
            preg_match($regExp, $dateString, $matches);
            if ($matches) {
                return (DateTimeImmutable::createFromFormat($format, $matches[0]))->format('Y-m-d');
            }
        }
        return "";
    }

    /**
     * Returns the title and colour of the product.
     * Avoided using hash functions to prevent collisions.
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->getTitle() . $this->getColour();
    }
}

