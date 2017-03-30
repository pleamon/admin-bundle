<?php

namespace P\AdminBundle\Component\Filesystem;

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
}
