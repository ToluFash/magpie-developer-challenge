<?php

use App\Services\Scraper\MagpieSmartphoneScraper;
use App\Services\Scraper\ScrapeFetcherInterface;
use App\Services\Scraper\ScrapeHelper;
use App\Services\Utils\DotEnvLoader;
use App\Services\Writer\JSONWriter;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;

use function DI\create;
use function DI\get;
use function DI\autowire;
use function DI\env;


DotEnvLoader::load(dirname(__DIR__).'/../.env');

return [
    LoggerInterface::class => create(Logger::class)->constructor(
        env('SCRAPER_LOG_CHANNEL'),
        [new StreamHandler(dirname(__DIR__).'/../'.getenv('SCRAPER_LOG_PATH'), Logger::DEBUG)]
    ),
    Client::class => create(),
    ScrapeFetcherInterface::class => autowire(ScrapeHelper::class),
    CssSelectorConverter::class => create(),
    'magpieURL' => env('MAGPIE_SMARTPHONES_URL'),
    'outputFile' => env('OUTPUT_FILE'),
    JSONWriter::class => create(),
    MagpieSmartphoneScraper::class => autowire()->constructor(
        get('magpieURL')
    )
];
