<?php

namespace App\Services\Writer;

class JSONWriter implements WriterInterface
{

    /**
     * @param array $data
     * @param string $outputFile
     * @return int
     */
    public function write(array $data, string $outputFile): int
    {
        return file_put_contents($outputFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
