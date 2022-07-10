<?php

namespace App\Tests\Services\Utils;

use App\Services\Utils\DotEnvLoader;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class DotEnvLoaderTest extends TestCase
{

    public function testLoad()
    {
        $env_vars = <<<ENV
###> SCRAPE PARAMS ###
PRODUCT_URL=https://producttest.com
###< SCRAPE PARAMS ###
ENV;
        $root = vfsStream::setup('project');
        $env_file = vfsStream::newFile('.env')->at($root)->setContent($env_vars);
        $this->assertNotTrue(getenv('MAGPIE_PRODUCTS_URL'));
        DotEnvLoader::load($env_file->url());
        $this->assertEquals('https://producttest.com', getenv('PRODUCT_URL'));
    }
}
