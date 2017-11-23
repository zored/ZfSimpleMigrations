<?php
/**
 * @category WebPT
 * @copyright Copyright (c) 2015 WebPT, INC
 * @author jgiberson
 * 6/4/15 2:20 PM
 */

namespace ZfSimpleMigrations\UnitTest\Controller;


use Zend\Mvc\Application;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use ZfSimpleMigrations\Controller\MigrateController;
use ZfSimpleMigrations\Controller\MigrateControllerFactory;
use ZfSimpleMigrations\Library\Migration;
use ZfSimpleMigrations\Library\MigrationSkeletonGenerator;

class MigrateControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ServiceManager */
    protected $service_manager;

    protected function setUp()
    {
        parent::setUp();
        $application = $this->getMock(Application::class, [], [], '', false);
        $this->service_manager = new ServiceManager([
            'services' => [
                'migrations.migration.foo' => $this->getMock(Migration::class, [], [], '', false),
                'migrations.skeleton-generator.foo' => $this->getMock(MigrationSkeletonGenerator::class, [], [], '', false),
                'Application' => $application
            ]
        ]);

        $application->expects($this->any())
            ->method('getMvcEvent')
            ->willReturn($mvcEvent = new MvcEvent());
        $mvcEvent->setRouteMatch($route_match = new RouteMatch(['name' => 'foo']));
    }


    public function test_it_returns_a_controller()
    {
        $instance = (new MigrateControllerFactory())->createService($this->service_manager);

        $this->assertInstanceOf(MigrateController::class, $instance,
            "factory should return an instance of " . MigrateController::class);
    }
}
