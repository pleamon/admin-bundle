<?php

namespace P\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
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
        $definition = $container->getDefinition('twig');
        $definition->addMethodCall('addGlobal', array('admin_menus', $admin_menus));
        $definition->addMethodCall('addGlobal', array('admin_title', $admin_title));
        $definition->addMethodCall('addGlobal', array('admin_search', $admin_search));
        $definition->addMethodCall('addGlobal', array('admin_pool', new Reference('p_admin.pool')));
    }
}
