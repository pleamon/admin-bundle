<?php

namespace P\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('p_admin');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('title')
                    ->validate()
                        ->ifTrue(function($v) {return $v == null;})
                        ->then(function($v) {return 'title';})
                    ->end()
                    ->defaultValue('title')
                ->end()
                ->scalarNode('base_template')
                    ->validate()
                        ->ifTrue(function($v) {return $v == null;})
                        ->then(function($v) {return 'PAdminBundle:layout:layout.html.twig';})
                    ->end()
                    ->defaultValue('PAdminBundle:layout:layout.html.twig')
                ->end()
                ->arrayNode('search')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('text')->defaultValue('')->end()
                        ->scalarNode('route')->defaultValue('')->end()
                    ->end()
                ->end()
                ->scalarNode('paginator_template')
                    ->validate()
                        ->ifTrue(function($v) {return $v == null;})
                        ->then(function($v) {return 'title';})
                    ->end()
                    ->defaultValue('PAdminBundle:layout:paginator.html.twig')
                ->end()
                ->arrayNode('menus')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('url')->defaultValue(null)->end()
                        ->scalarNode('route')->defaultValue(null)->end()
                        ->scalarNode('text')->defaultValue('')->end()
                        ->scalarNode('label')->defaultValue('')->end()
                        ->scalarNode('icon')->defaultValue('')->end()
                        ->arrayNode('role')
                            ->defaultValue(array())
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('items')
                            ->defaultValue(array())
                            ->useAttributeAsKey('id')
                            ->prototype('array')
                            ->children()
                                ->scalarNode('children')->defaultValue(true)->end()
                                ->scalarNode('url')->defaultValue(null)->end()
                                ->scalarNode('route')->defaultValue(null)->end()
                                ->scalarNode('text')->defaultValue('')->end()
                                ->scalarNode('label')->defaultValue('')->end()
                                ->scalarNode('icon')->defaultValue('')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
