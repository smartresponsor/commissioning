<?php

declare(strict_types=1);

namespace App\Commissioning\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class CommissioningExtension extends Extension
{
    /**
     * Loads Commissioning service configuration for host applications.
     *
     * @param array<int, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new CommissionConfiguration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        $container->setParameter('commissioning.default_plan_code', $processedConfig['default_plan_code']);
        $container->setParameter('commissioning.development_fallbacks', $processedConfig['development_fallbacks']);

        $configPath = dirname(__DIR__, 2).'/config';

        if (is_file($configPath.'/services.yaml')) {
            $loader = new YamlFileLoader($container, new FileLocator($configPath));
            $loader->load('services.yaml');
        }
    }

    public function getAlias(): string
    {
        return 'commissioning';
    }
}
