<?php


namespace ZfSimpleMigrations\Library\Compatibility;

use Interop\Container\ContainerInterface;
use Zend\Di\ServiceLocatorInterface;

/**
 * @see \Zend\ServiceManager\FactoryInterface
 * @deprecated Remove it as soon as possible.
 */
trait ZF3FactoryTrait
{
    use ZF3RootContainerTrait;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->createService($container);
    }

    abstract public function createService(ServiceLocatorInterface $serviceLocator);

}