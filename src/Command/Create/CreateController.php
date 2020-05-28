<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Create;

use Zend\Console\ColorInterface as Color;
use ZFrapidCore\Command\AbstractCommand;

/**
 * Class CreateController
 *
 * @package ZF2rapid\Command\Create
 */
class CreateController extends AbstractCommand
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
            'ZF2rapid\Task\Check\ModuleExists',
            'ZF2rapid\Task\CreateStructure\CreateControllerStructure',
            'ZF2rapid\Task\GenerateClass\GenerateControllerClass',
            'ZF2rapid\Task\GenerateFactory\GenerateControllerFactory',
            'ZF2rapid\Task\UpdateConfig\UpdateControllerConfig',
            'ZF2rapid\Task\GenerateAction\GenerateActionMethod',
            'ZF2rapid\Task\GenerateAction\GenerateActionView',
        ];

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_create_controller_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'command_create_controller_stop',
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
