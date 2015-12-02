<?php

namespace P\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use P\AdminBundle\DependencyInjection\Compiler\TwigCompilerPass;
use Symfony\Component\Console\Application;
use Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;
use Symfony\Component\Filesystem\FileSystem;

class PAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigCompilerPass());
    }
}
