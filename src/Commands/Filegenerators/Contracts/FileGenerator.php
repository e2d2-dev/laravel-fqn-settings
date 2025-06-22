<?php

namespace Betta\Settings\Commands\Filegenerators\Contracts;

interface FileGenerator
{
    public function generate(): string;
}
