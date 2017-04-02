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
                ->scalarNode('base_template')
                    ->defaultValue('PAdminBundle:layout:layout.html.twig')
                ->end()
                ->arrayNode('search')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('text')->defaultValue('')->end()
                        ->scalarNode('route')->defaultValue('')->end()
                    ->end()
                ->end()
                ->arrayNode('modal')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('count')->defaultValue(2)->end()
                    ->end()
                ->end()
                ->scalarNode('paginator_template')
                    ->defaultValue('PAdminBundle:layout:paginator.html.twig')
                ->end()
                ->arrayNode('route')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('exclude')
                            ->addDefaultsIfNotSet()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('languages')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                    ->end()
                ->end()

            ->end()
            ;

        return $treeBuilder;
    }
}
