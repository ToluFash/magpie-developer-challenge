<?php

namespace App\Command\Services\Writer;

interface WriterInterface
{

    /**
     * @param array $data
     * @param string $outputFile
     * @return int
     */
    public function write(array $data, string $outputFile): int;
}
