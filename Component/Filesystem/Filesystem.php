<?php

namespace P\AdminBundle\Component\Filesystem;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class Filesystem extends SymfonyFilesystem
{
    public function scandir($dir = '.', array $excludes = array('.', '..'))
    {
        $files = scandir($dir);

        $_files = array_filter($files, function($file) use ($excludes){
            return !(in_array($file, $excludes));
        });
        return $_files;
    }

    public function bytesToSize($bytes)
    {
        $k = 1024;
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $i = floor(log($bytes) / log($k));
        $rs = round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
        return $rs;
    }
}
