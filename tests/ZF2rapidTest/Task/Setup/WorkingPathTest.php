<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapidTest\Task\Setup;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Zend\Stdlib\Parameters;
use ZF\Console\Route;
use ZFrapidCore\Console\ConsoleInterface;
use ZF2rapid\Task\Setup\WorkingPath;

/**
 * Class WorkingPathTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class WorkingPathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Route|PHPUnit_Framework_MockObject_MockObject
     */
    private $route;

    /**
     * @var ConsoleInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $console;

    /**
     * @var Parameters
     */
    private $parameters;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->route = $this->getMockBuilder('ZF\Console\Route')
            ->setConstructorArgs(['test', 'test'])
            ->getMock();

        $this->console = $this->getMockBuilder(
            'ZFrapidCore\Console\ConsoleInterface'
        )->getMock();

        $this->parameters = new Parameters();
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new WorkingPath();

        $this->assertInstanceOf('ZF2rapid\Task\Setup\WorkingPath', $task);
    }

    /**
     *  Test result type of invocation
     */
    public function testInvocation()
    {
        $task = new WorkingPath();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }

    /**
     *  Test path param
     */
    public function testPathParamNonExistingPath()
    {
        $paramValueMap = [
            ['workingPath', null, '/path/to/project']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new WorkingPath();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals('/path/to/project', $this->parameters->workingPath);

        $this->assertEquals(
            'APPLICATION_ROOT', $this->parameters->applicationRootConstant
        );

        $this->assertTrue(defined('APPLICATION_ROOT'));
        $this->assertEquals('/path/to/project', constant('APPLICATION_ROOT'));
    }

    /**
     *  Test path param
     */
    public function testPathParamExistingPath()
    {
        $paramValueMap = [
            ['workingPath', null, '/tmp']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new WorkingPath();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals('/tmp', $this->parameters->workingPath);
    }
}
