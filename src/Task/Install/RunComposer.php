<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Install;

use ZFrapidCore\Task\AbstractTask;

/**
 * Class RunComposer
 *
 * @package ZF2rapid\Task\Install
 */
class RunComposer extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine('task_install_run_composer_running');

        // start installation of dependencies
        exec(
            'php ' . $this->params->workingPath
            . '/composer.phar --working-dir=' . $this->params->workingPath
            . ' update -q',
            $output,
            $return
        );

        return 0;
    }

}