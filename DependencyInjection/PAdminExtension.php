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
        $container->setParameter('p.admin.base_template', isset($config['base_template'])?$config['base_template']:'');
        $container->setParameter('p.paginator.template', $config['paginator_template']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $this->addAssetsBundle($container);
        $this->addFormThemes($container);
        $this->setParameterToTwig($container, $config);
    }

    public function setMenuTemplate($container)
    {
        $container->setParameter('knp_menu.renderer.twig.template', $container->getParameter('p.sidebar.template'));
    }

    public function addFormThemes($container)
    {
        $themes = $container->getParameter('twig.form.resources');
        $themes = array_merge($themes, array(
            'PAdminBundle:Form:image_field.html.twig',
            'PAdminBundle:Form:upload_image_field.html.twig',
            'PAdminBundle:Form:label_field.html.twig',
            'PAdminBundle:Form:custom_field.html.twig',
            'PAdminBundle:Form:region_field.html.twig',
            'PAdminBundle:Form:datepicker_field.html.twig',
            'PAdminBundle:Form:datetimepicker_field.html.twig',
            'PAdminBundle:Form:editor_field.html.twig',
            'PAdminBundle:Form:rich_text_field.html.twig',
            'PAdminBundle:Form:recaptcha_field.html.twig',
        ));
        $container->setParameter('twig.form.resources', $themes);
    }

    public function addAssetsBundle($container)
    {
        $asseticBundles = $container->getParameter('assetic.bundles');
        array_merge($asseticBundles, array(
            'PAdminBundle',
            'PFileBundle',
            'PNotificationBundle',
        ));
        $container->setParameter('assetic.bundles', $asseticBundles);
    }

    public function addAssetsInputs($container)
    {
        $formulae = array(
            'font_css' => array(
                array('@PAdminBundle/Resources/public/googlefonts/fonts.css'),
                array(),
                array('googlefonts/fonts.css')
            ),
            'font1' => array (
                array('@PAdminBundle/Resources/public/googlefonts/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf'),
                array (),
                array (
                    //'output' => 'googlefonts/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf'
                    'output' => 'test'
                )
            )
        );

        //$container->getDefinition('assetic.config_resource')->replaceArgument(0, $formulae);
    }

    public function setParameterToTwig($container, $config)
    {
        //$def = $container->getDefinition('twig');
        //$def->addMethodCall('addGlobal', array($key, new Reference($global['id'])));
        //$def->addMethodCall('addGlobal', array('admin_config', $config));
    }
}
