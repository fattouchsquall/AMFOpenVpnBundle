<?php

namespace AMF\OpenVpnBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('amf_openvpn');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $this->addOpenVpnServersSection($rootNode);
        $this->addOpenVpnConfSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds section of each server config to gloabl config.
     *
     * @param ArrayNodeDefinition $node The root node of the config for this bundle.
     */
    private function addOpenVpnServersSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('servers')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('telnet_ip')->defaultValue("127.0.0.1")->cannotBeEmpty()->end()
                            ->integerNode('telnet_port')->defaultValue("7500")->end()
                            ->scalarNode('telnet_password')->defaultNull()->end()
                            ->scalarNode('name')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds section of setting to gloabl config.
     *
     * @param ArrayNodeDefinition $node The root node of the config for this bundle.
     */
    private function addOpenVpnConfSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('config')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('reload')->defaultValue("5")->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end();
    }
}
