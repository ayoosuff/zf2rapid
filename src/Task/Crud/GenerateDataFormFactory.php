<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\FileGenerator\ClassFileGenerator;
use ZF2rapid\Generator\CrudGenerator\DataFormFactoryGenerator;
use ZFrapidCore\Task\AbstractTask;

/**
 * Class GenerateDataFormFactory
 *
 * @package ZF2rapid\Task\Crud
 */
class GenerateDataFormFactory extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine(
            'task_generate_factory_writing',
            [
                'form'
            ]
        );

        // set factory file
        $factoryFile = $this->params->applicationFormDir . '/' . str_replace('Entity', '', $this->params->paramEntityClass) . 'DataFormFactory.php';

        // check if factory file exists
        if (file_exists($factoryFile)) {
            $this->console->writeFailLine(
                'task_generate_factory_exists',
                [
                    'form',
                    $this->console->colorize(
                        $this->params->paramModule . 'Form', Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // create class
        $class = new DataFormFactoryGenerator(
            str_replace('Entity', '', $this->params->paramEntityClass) . 'DataForm',
            $this->params->paramModule,
            $this->params->paramEntityModule,
            $this->params->paramEntityClass,
            $this->params->loadedTables,
            $this->params->config
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($factoryFile, $file->generate());

        return 0;
    }
}