<?php

namespace P\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Bundle\AsseticBundle\Factory\Resource\ConfigurationResource;

class DashboardCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('p.admin.dashboard');
        $services = $container->findTaggedServiceIds('p.admin.dashboard.widget');
        foreach($services as $service => $attributes) {
            $definition->addMethodCall(
                'register',
                array($container->getDefinition($service), $service, $attributes),
                $service
            );
        }

    }
}

