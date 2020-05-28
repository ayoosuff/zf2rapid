<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use ZFrapidCore\Console\Console;
use ZFrapidCore\Task\AbstractTask;

/**
 * Class ChooseApplicationConfigFile
 *
 * @package ZF2rapid\Task\Module
 */
class ChooseApplicationConfigFile extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for project
        if (!$this->params->paramWithProject) {
            return 0;
        }

        // check for activation
        if (!$this->params->paramActivation) {
            return 0;
        }

        // check for specified config file
        if ($this->params->paramConfigFile) {
            $this->params->configFile = $this->params->paramConfigFile;
        } else {
            // set filter dirs
            $filterDirs = ['..', '.', 'autoload'];

            // get existing config files
            $configFiles = array_values(
                array_diff(scandir($this->params->projectConfigDir), $filterDirs)
            );

            // remove dirs
            foreach ($configFiles as $key => $configFile) {
                if (is_dir($this->params->projectConfigDir . '/' . $configFile)) {
                    unset ($configFiles[$key]);
                }
            }

            $configFiles = array_values($configFiles);

            // set indention
            $spaces = Console::INDENTION_PROMPT_OPTIONS;

            // add option keys
            foreach ($configFiles as $key => $file) {
                $configFiles[$spaces . chr(ord('a') + $key)] = $file;
                unset($configFiles[$key]);
            }

            $chosenConfigFile = $this->console->writeSelectPrompt(
                'task_module_choose_config_file_prompt',
                $configFiles
            );

            // set skeleton application name
            $this->params->configFile = $configFiles[$spaces . $chosenConfigFile];
        }

        return 0;
    }
}