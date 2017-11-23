<?php


namespace ZfSimpleMigrations\Library;

use RuntimeException;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfSimpleMigrations\Library\Compatibility\ZF3AbstractFactoryTrait;
use ZfSimpleMigrations\Model\MigrationVersionTable;

class MigrationAbstractFactory implements AbstractFactoryInterface
{
    use ZF3AbstractFactoryTrait;

    const FACTORY_PATTERN = '/migrations\.migration\.(.*)/';
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
     * @return Migration
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $serviceLocator = $this->getRootContainer($serviceLocator);

        $config = $serviceLocator->get('Config');

        preg_match(self::FACTORY_PATTERN, $name, $matches)
        || preg_match(self::FACTORY_PATTERN, $requestedName, $matches);

        $name = $matches[1];

        if (! isset($config['migrations'][$name])) {
            throw new RuntimeException(sprintf("`%s` does not exist in migrations configuration", $name));
        }

        $migration_config = $config['migrations'][$name];

        $adapter_name = isset($migration_config['adapter'])
            ? $migration_config['adapter'] : 'Zend\Db\Adapter\Adapter';
        /** @var $adapter \Zend\Db\Adapter\Adapter */
        $adapter = $serviceLocator->get($adapter_name);


        $output = null;
        if (isset($migration_config['show_log']) && $migration_config['show_log']) {
            $console = $serviceLocator->get('console');
            $output = new OutputWriter(function ($message) use ($console) {
                $console->write($message . "\n");
            });
        }

        /** @var MigrationVersionTable $version_table */
        $version_table = $serviceLocator->get('migrations.versiontable.' . $adapter_name);

        $migration = new Migration($adapter, $migration_config, $version_table, $output);

        $migration->setServiceLocator($serviceLocator);

        return $migration;
    }
}
