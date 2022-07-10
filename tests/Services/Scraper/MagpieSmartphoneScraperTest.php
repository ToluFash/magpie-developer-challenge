<?php

namespace App\Tests\Services\Scraper;

use App\Services\Scraper\MagpieSmartphoneScraper;
use App\Services\Scraper\ScrapeFetcherInterface;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class MagpieSmartphoneScraperTest extends TestCase
{

    public function testNew()
    {
        $magpieUrl = 'https://www.magpiehq.com/developer-challenge/smartphones';
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $fetcher = $this->getMockBuilder(ScrapeFetcherInterface::class)->getMock();
        $cssConverter= $this->getMockBuilder(CssSelectorConverter::class)->getMock();

        $scraper = new MagpieSmartphoneScraper($magpieUrl, $fetcher, $cssConverter, $logger);
        $this->assertTrue($scraper instanceof MagpieSmartphoneScraper);
    }
    public function testRunScrape()
    {
        $dir = dirname(__DIR__);
        $magpieUrl = 'https://www.magpiehq.com/developer-challenge/smartphones';
        $pages = [
            "https://www.magpiehq.com/developer-challenge/smartphones?page=1" => $dir . '/Scraper/testfiles/page1.html',
            "https://www.magpiehq.com/developer-challenge/smartphones?page=2" => $dir . '/Scraper/testfiles/page2.html',
            "https://www.magpiehq.com/developer-challenge/smartphones?page=3" => $dir . '/Scraper/testfiles/page3.html',
            "https://www.magpiehq.com/developer-challenge/smartphones?page=4" => $dir . '/Scraper/testfiles/page4.html'
        ];
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $fetcher = $this->getMockBuilder(ScrapeFetcherInterface::class)->getMock();
        $fetcher->expects($this->any())
            ->method("fetchDocument")
            ->will($this->returnCallback(function() use ($pages)
            {
                $args = func_get_args();
                return $this->readFile($args[0], $pages[$args[0]]);
            }));
        $cssConverter= new CssSelectorConverter();
        $scraper = new MagpieSmartphoneScraper($magpieUrl, $fetcher, $cssConverter, $logger);
        $scraper->runScrape();
        $this->assertCount(20, $scraper->getProducts());
    }

    public function readFile(string $url, string $path): Crawler
    {
        return new Crawler(file_get_contents($path), $url);
    }
}
