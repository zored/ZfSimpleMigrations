<?php


namespace ZfSimpleMigrations\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfSimpleMigrations\Library\Compatibility\ZF3AbstractFactoryTrait;

class MigrationVersionTableAbstractFactory implements AbstractFactoryInterface
{
    use ZF3AbstractFactoryTrait;

    const FACTORY_PATTERN = '/migrations\.version-?table\.(.*)/';
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return preg_match(self::FACTORY_PATTERN, $name) || preg_match(self::FACTORY_PATTERN, $requestedName);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        // $matches will be set by first preg_match if it matches, or second preg_match if it doesnt
        preg_match(self::FACTORY_PATTERN, $name, $matches)
        || preg_match(self::FACTORY_PATTERN, $requestedName, $matches);

        $adapter_name = $matches[1];

        /** @var $tableGateway TableGateway */
        $tableGateway = $serviceLocator->get('migrations.versiontablegateway.' . $adapter_name);
        $table = new MigrationVersionTable($tableGateway);
        return $table;
    }
}
