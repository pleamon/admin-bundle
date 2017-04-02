<?php

namespace P\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use P\AdminBundle\DependencyInjection\Compiler\TwigCompilerPass;
use P\AdminBundle\DependencyInjection\Compiler\AssetCompilerPass;
use P\AdminBundle\DependencyInjection\Compiler\DoctrineCompilerPass;
use P\AdminBundle\DependencyInjection\Compiler\ConfigCompilerPass;
use P\AdminBundle\DependencyInjection\Compiler\DashboardCompilerPass;

class PAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigCompilerPass());
        $container->addCompilerPass(new AssetCompilerPass());
        $container->addCompilerPass(new DoctrineCompilerPass());
        $container->addCompilerPass(new ConfigCompilerPass());
        $container->addCompilerPass(new DashboardCompilerPass());
    }
}
