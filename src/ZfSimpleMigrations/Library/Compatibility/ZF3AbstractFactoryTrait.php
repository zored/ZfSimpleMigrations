<?php


namespace ZfSimpleMigrations\Library\Compatibility;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @see \Zend\ServiceManager\AbstractFactoryInterface
 * @deprecated Remove me as soon as possible.
 */
trait ZF3AbstractFactoryTrait
{
    use ZF3RootContainerTrait;

    abstract public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName);

    abstract public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName);

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return $this->canCreateServiceWithName($this->getServiceLocator($container), $requestedName, $requestedName);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->createServiceWithName($this->getServiceLocator($container), $requestedName, $requestedName);
    }

    /**
     * @param ContainerInterface $container
     * @return ServiceLocatorInterface
     * @throws ServiceNotCreatedException
     */
    private function getServiceLocator(ContainerInterface $container)
    {
        if (!$container instanceof ServiceLocatorInterface) {
            throw new ServiceNotCreatedException('Wrong container type: ' . get_class($container));
        }

        return $container;
    }
}