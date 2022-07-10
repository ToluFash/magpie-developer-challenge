<?php

namespace App\Command;

use App\Services\Scraper\MagpieSmartphoneScraper;
use App\Services\Writer\JSONWriter;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
require 'vendor/autoload.php';

/**
 * Command to run the Scraping for the Magpie website, a better way might be to have a
 * Symfony Style Console System, where DI can be properly initialized, and services injected appropriately
 * and commands to run will come in as arguments to the console. Also defers auto-loading and env loading to the console.
 *
 * This changes the name for Scrape to ScrapeMagpieSmartphonesCommand, because the use case is much more specific than just scrape.
 */
class ScrapeMagpieSmartphonesCommand implements CommandInterface
{
    private Container $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $this->container = $containerBuilder->addDefinitions(dirname(__DIR__).'/Config/DIConfig.php')
            ->build();
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function execute()
    {
        (new self())->run();
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function run(): void
    {
        $scraper = $this->container->get(MagpieSmartphoneScraper::class);
        $scraper->runScrape();
        $jsonWriter = $this->container->get(JSONWriter::class);
        $jsonWriter->write(array_values($scraper->getProducts()), $this->container->get('outputFile'));

    }

}
ScrapeMagpieSmartphonesCommand::execute();
