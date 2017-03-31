<?php

namespace P\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Bundle\AsseticBundle\Factory\Resource\ConfigurationResource;

class DoctrineCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {

        $definition = $container->getDefinition('doctrine.orm.default_entity_listener_resolver');
        $services = $container->findTaggedServiceIds('doctrine.entity_listener');
        foreach($services as $service => $attributes) {
            $definition->addMethodCall(
                'registerService',
                array($container->getDefinition($service)->getClass(), $service),
                $service
            );
        }
    }
}
