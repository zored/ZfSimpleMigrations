<?php


namespace ZfSimpleMigrations\Controller;


use Zend\Console\Request;
use Zend\Mvc\Console\Router\RouteMatch;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfSimpleMigrations\Library\Compatibility\ZF3FactoryTrait;
use ZfSimpleMigrations\Library\Migration;
use ZfSimpleMigrations\Library\MigrationSkeletonGenerator;

class MigrateControllerFactory implements FactoryInterface
{
    use ZF3FactoryTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $this->getRootContainer($serviceLocator);

        /** @var RouteMatch $routeMatch */
        $routeMatch = $serviceLocator->get('Application')->getMvcEvent()->getRouteMatch();

        $name = $routeMatch->getParam('name', 'default');

        /** @var Migration $migration */
        $migration = $serviceLocator->get('migrations.migration.' . $name);
        /** @var MigrationSkeletonGenerator $generator */
        $generator = $serviceLocator->get('migrations.skeleton-generator.' . $name);

        $controller = new MigrateController();

        $controller->setMigration($migration);
        $controller->setSkeletonGenerator($generator);

        return $controller;
    }
}
