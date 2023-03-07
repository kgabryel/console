<?php

namespace Frankie\Console;

use Frankie\Storage\Directory\Directory;

class Installer
{
    public static function install(): void
    {
        $path = str_replace('/', DIRECTORY_SEPARATOR, sprintf('%s%s', __DIR__, '/../bin'));
        $target = str_replace('/', DIRECTORY_SEPARATOR, sprintf('%s%s', __DIR__, '/../../../../'));
        (new Directory($path))->getFilesList()->get('console')->copy($target);
    }
}
