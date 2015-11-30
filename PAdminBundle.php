<?php

namespace P\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use P\AdminBundle\DependencyInjection\Compiler\TwigCompilerPass;

class PAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigCompilerPass());
    }
}
