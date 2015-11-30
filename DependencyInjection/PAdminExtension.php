<?php

namespace P\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('p.admin.menus', $config['menus']);
        $container->setParameter('p.admin.title', $config['title']);
        $container->setParameter('p.admin.search', $config['search']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $this->addAssetsBundle($container);
        $this->setParameterToTwig($container, $config);
    }

    public function setMenuTemplate($container)
    {
        $container->setParameter('knp_menu.renderer.twig.template', $container->getParameter('p.sidebar.template'));
    }

    public function addAssetsBundle($container)
    {
        $asseticBundles = $container->getParameter('assetic.bundles');
        array_push($asseticBundles, 'PAdminBundle');
        $container->setParameter('assetic.bundles', $asseticBundles);
    }

    public function setParameterToTwig($container, $config)
    {
        //$def = $container->getDefinition('twig');
        //$def->addMethodCall('addGlobal', array($key, new Reference($global['id'])));
        //$def->addMethodCall('addGlobal', array('admin_config', $config));
    }
}
