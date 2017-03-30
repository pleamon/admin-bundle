<?php

namespace P\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TwigCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('twig')) {
            return;
        }

        $definition = $container->getDefinition('twig');

        $base_template = $container->getParameter('p.admin.base_template');
        $definition->addMethodCall('addGlobal', array('admin_base_template', $base_template));
        $definition->addMethodCall('addGlobal', array('padmin', new Reference('p.admin.twig.variable')));
    }
}
