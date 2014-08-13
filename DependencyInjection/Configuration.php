<?php

/*
 * This file is part fo the Webuni Assetic Bundle
 */

namespace Webuni\AsseticBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        return $treeBuilder
            ->root('webuni_assetic')
                // filters
                ->fixXmlConfig('filter')
                ->children()
                    ->arrayNode('filters')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('csswebrewrite')
                                ->canBeDisabled()
                                ->children()
                                    ->variableNode('apply_to')
                                        ->defaultValue(array('\.css$'))
                                        ->beforeNormalization()
                                            ->ifTrue(function ($v) { return is_string($v); })
                                            ->then(function ($v) { return array($v); })
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('lessphp')
                                ->canBeDisabled()
                                ->children()
                                    ->variableNode('apply_to')
                                        ->defaultValue(array('\.less$'))
                                        ->beforeNormalization()
                                            ->ifTrue(function ($v) { return is_string($v); })
                                            ->then(function ($v) { return array($v); })
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
