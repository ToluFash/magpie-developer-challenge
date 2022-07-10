<?php

namespace App\Services\Writer;

interface WriterInterface
{

    /**
     * @param array $data
     * @param string $outputFile
     * @return int
     */
    public function write(array $data, string $outputFile): int;
}
