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
        $admin_menus = $container->getParameter('p.admin.menus');
        $admin_title = $container->getParameter('p.admin.title');
        $admin_search = $container->getParameter('p.admin.search');
        $base_template = $container->getParameter('p.admin.base_template');

        $definition = $container->getDefinition('twig');

        $definition->addMethodCall('addGlobal', array('admin_menus', $admin_menus));
        $definition->addMethodCall('addGlobal', array('admin_title', $admin_title));
        $definition->addMethodCall('addGlobal', array('admin_search', $admin_search));
        $definition->addMethodCall('addGlobal', array('base_template', $base_template));
        $definition->addMethodCall('addGlobal', array('p', new Reference('p_admin.pool')));
    }
}
