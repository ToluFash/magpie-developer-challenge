<?php

namespace App\Command\Command;

interface CommandInterface
{

    public static function execute();
    public function run(): void;

}
