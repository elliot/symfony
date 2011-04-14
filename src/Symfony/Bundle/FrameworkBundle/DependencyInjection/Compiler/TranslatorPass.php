<?php

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TranslatorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('translator')) {
            return;
        }

        // Compile loaders
        $loaders = array();
        foreach ($container->findTaggedServiceIds('translation.loader') as $id => $attributes) {
            $loaders[$id] = $attributes[0]['alias'];
        }
        $container->setParameter('translation.loaders', $loaders);
        
        // Drop resources that don't have a laoder to stop an exception being thrown
        $uncheckedResources = array();
        
        $resources = $container->getParameter('translation.resources');
        foreach ($resources as $resource) {
            if (! in_array($resource[0], $loaders)) {
                // The format does not ahve a registered loader
                // How to handle??
            } else {
                $checkedResources[] = $resource;
            }
        }
        
        $container->setParameter('translation.resources', $checkedResources);
    }
}
