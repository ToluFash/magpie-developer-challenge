<?php

namespace App\Services\Scraper;

use App\Entity\Smartphone;
use Psr\Log\LoggerInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;

class MagpieSmartphoneScraper
{
    private CssSelectorConverter $cssConverter;
    private ScrapeFetcherInterface $fetcher;
    private string $urlBase;
    private LoggerInterface $logger;
    private array $products;

    public function __construct(
        string $magpieURL,
        ScrapeFetcherInterface $fetcher,
        CssSelectorConverter $cssConverter,
        LoggerInterface $logger

    ) {
        $this->urlBase = $magpieURL;
        $this->fetcher = $fetcher;
        $this->logger = $logger;
        $this->cssConverter = $cssConverter;
        $this->products = array();
    }

    public function runScrape()
    {
        $pagedUrl = $this->urlBase.'?page=';
        $pageNo = 1;
        $this->logger->info("Starting Scrape for $this->urlBase started!");
        $productDocs = $this->fetcher->fetchDocument(
            $pagedUrl.$pageNo)->filterXPath($this->cssConverter->toXPath('div > div > div.product')
        );
        while ($productDocs->count() > 0) {
            for ($productEq = 0; $productEq < $productDocs->count(); $productEq++) {
                $colourCount = $productDocs->eq($productEq)->filter('div.px-2 > span')->count();
                for ($colourEq = 0; $colourEq < $colourCount; $colourEq++) {
                    $phoneDesc = new ScrapeSmartPhoneDetails($productDocs->eq($productEq), $colourEq);
                    if(!isset($this->products[$phoneDesc->getHash()])) {
                        $this->products[$phoneDesc->getHash()] = new Smartphone($phoneDesc);
                    }
                }
            }
            $productDocs = $this->fetcher->fetchDocument(
                $pagedUrl.++$pageNo)->filterXPath($this->cssConverter->toXPath('div > div > div.product')
            );
        }

        $this->logger->info(sprintf(
                "Scrape Completed for %s, %d products retrieved in unique items.}",
                $this->urlBase,
                count($this->products
                )
            )
        );
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

}
