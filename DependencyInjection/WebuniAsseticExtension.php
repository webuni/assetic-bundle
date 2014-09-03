<?php
/*
 * This file is part of Webuni Assetic Bundle
 */

namespace Webuni\AsseticBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Martin Hasoň <martin.hason@gmai.com>
 */
class WebuniAsseticExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('controller.xml');

        foreach ($configs['filters'] as $name => $settings) {
            if (!$settings['enabled']) {
                continue;
            }

            $loader->load('filters/'.$name.'.xml');

            foreach ($settings['apply_to'] as $i => $pattern) {
                $worker = new DefinitionDecorator('assetic.worker.ensure_filter');
                $worker->replaceArgument(0, '/'.$pattern.'/');
                $worker->replaceArgument(1, new Reference('assetic.filter.'.$name));
                $worker->addTag('assetic.factory_worker');

                $container->setDefinition('assetic.filter.'.$name.'.worker'.$i, $worker);
            }
        }
    }
}
