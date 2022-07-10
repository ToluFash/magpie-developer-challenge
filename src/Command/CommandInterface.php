<?php

namespace App\Command;

interface CommandInterface
{

    public static function execute();
    public function run(): void;
}
