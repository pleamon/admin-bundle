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
                ->scalarNode('favicon')
                    ->defaultValue('P Admin')
                ->end()
                ->scalarNode('title')
                    ->defaultValue('P Admin System')
                ->end()
                ->scalarNode('description')
                    ->defaultValue('P Admin System Description')
                ->end()
                ->scalarNode('copyright')
                    ->defaultValue('PAdmin we app framework base on Symfony 3 © 2017')
                ->end()
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
                ->arrayNode('amqp')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')->defaultValue(null)->end()
                        ->scalarNode('vhost')->defaultValue('/')->end()
                        ->scalarNode('port')->defaultValue(5672)->end()
                        ->scalarNode('login')->defaultValue(null)->end()
                        ->scalarNode('password')->defaultValue(null)->end()
                        ->scalarNode('api_port')->defaultValue(15672)->end()
                        ->scalarNode('api_user')->defaultValue('guest')->end()
                        ->scalarNode('api_password')->defaultValue('guest')->end()
                    ->end()
                ->end()
                ->arrayNode('baidu')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('ak')->defaultValue(null)->end()
                        ->scalarNode('sk')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
