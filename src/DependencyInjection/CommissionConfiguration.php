<?php

declare(strict_types=1);

namespace App\Commissioning\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configures the Commissioning Symfony dependency-injection surface represented by CommissionConfiguration for host applications.
 */
final class CommissionConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('commissioning');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_plan_code')->defaultValue('default')->end()
                ->booleanNode('development_fallbacks')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
}
