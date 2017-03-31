<?php

namespace P\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

use P\AdminBundle\Component\Filesystem\Filesystem;

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

        $container->setParameter('p.admin.search', $config['search']);
        $container->setParameter('p.admin.modal', $config['modal']);
        $container->setParameter('p.admin.base_template', $config['base_template']);
        $container->setParameter('p.admin.route', $config['route']);
        $container->setParameter('p.paginator.template', $config['paginator_template']);
        $container->setParameter('p.amqp.credentials', $config['amqp']);
        $container->setParameter('p.baidu', $config['baidu']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $this->addAssetsBundle($container);
        $this->addFormThemes($container);
    }

    public function setMenuTemplate($container)
    {
        $container->setParameter('knp_menu.renderer.twig.template', $container->getParameter('p.sidebar.template'));
    }

    public function addFormThemes($container)
    {
        $themes = $container->getParameter('twig.form.resources');
        $dir = __DIR__ . '/../Resources/views/Form';

        $fs = new Filesystem();

        $files = array_map(function($file) {
            return sprintf('PAdminBundle:Form:%s', $file);
        }, $fs->scandir($dir));
        $themes = array_merge($themes, $files);
        $container->setParameter('twig.form.resources', $themes);
    }

    public function addAssetsBundle($container)
    {
        $asseticBundles = $container->getParameter('assetic.bundles');
        array_merge($asseticBundles, array(
            'PAdminBundle',
        ));
        $container->setParameter('assetic.bundles', $asseticBundles);
    }
}
