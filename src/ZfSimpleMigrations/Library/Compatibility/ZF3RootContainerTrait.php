<?php


namespace ZfSimpleMigrations\Library\Compatibility;


use Zend\ServiceManager\AbstractPluginManager;

trait ZF3RootContainerTrait
{
    protected function getRootContainer($container)
    {
        if (!$container instanceof AbstractPluginManager) {
            return $container;
        }

        if (!$container instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
            return $container;
        }

        return $container->getServiceLocator();
    }

}