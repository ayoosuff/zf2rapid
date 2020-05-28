<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Delete;

use Zend\Console\ColorInterface as Color;
use ZFrapidCore\Command\AbstractCommand;

/**
 * Class DeleteControllerFactory
 *
 * @package ZF2rapid\Command\Delete
 */
class DeleteControllerFactory extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = [
            'ZF2rapid\Task\Setup\WorkingPath',
            'ZF2rapid\Task\Setup\ConfigFile',
            'ZF2rapid\Task\Setup\Params',
            'ZF2rapid\Task\Check\ModulePathExists',
            'ZF2rapid\Task\Check\ControllerExists',
            'ZF2rapid\Task\DeleteFactory\DeleteControllerFactory',
            'ZF2rapid\Task\UpdateConfig\UpdateControllerConfig',
        ];

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_delete_controller_factory_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'command_delete_controller_factory_stop',
            [
                $this->console->colorize(
                    $this->params->paramController, Color::GREEN
                ),
                $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                )
            ]
        );
    }
}
