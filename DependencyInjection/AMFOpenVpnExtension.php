<?php

namespace AMF\OpenVpnBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AMFOpenVpnExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (isset($config['servers'])) {
            $container->setParameter('amf_openvpn.servers', $config['servers']);
        }

        if (isset($config['config'])) {
            $this->remapParametersNamespaces($config, $container, array('config' => 'amf_openvpn.config.%s'));
        }

        $loader->load('server_management.yml');
    }

    /**
     * Maps parameters to add them in container.
     *
     * @param array            $config     The gloabl config of the security bundle.
     * @param ContainerBuilder $container  The container for dependency injection.
     * @param array            $namespaces Config namespaces to add as parameters in the container.
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $namespace => $map) {
            if (isset($namespace)) {
                if (!array_key_exists($namespace, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$namespace];
            } else {
                $namespaceConfig = $config;
            }

            foreach ($namespaceConfig as $name => $value) {
                $container->setParameter(sprintf($map, $name), $value);
            }
        }
    }
}
