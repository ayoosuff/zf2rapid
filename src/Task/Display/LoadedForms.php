<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Display;

use Zend\Console\ColorInterface as Color;
use ZFrapidCore\Task\AbstractTask;

/**
 * Class LoadedForms
 *
 * @package ZF2rapid\Task\Display
 */
class LoadedForms extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output done message
        $this->console->writeTaskLine(
            'task_display_loaded_forms_found_in_path',
            [
                $this->console->colorize(
                    $this->params->workingPath, Color::GREEN
                )
            ]
        );

        // loop through modules
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $this->console->writeListItemLine(
                'task_display_loaded_forms_module_class',
                [
                    $this->console->colorize(
                        $moduleName, Color::GREEN
                    ),
                    $this->console->colorize(
                        get_class($moduleObject), Color::BLUE
                    )
                ]
            );

            // check for empty form list
            if (empty($this->params->loadedForms[$moduleName])) {
                $this->console->writeListItemLineLevel2(
                    'task_display_loaded_forms_no_forms'
                );

                continue;
            }

            // loop through controllers
            foreach (
                $this->params->loadedForms[$moduleName]
                as $pluginType => $pluginList
            ) {
                $this->console->writeListItemLineLevel2(
                    'task_display_loaded_forms_type',
                    [
                        $this->console->colorize(
                            $pluginType, Color::GREEN
                        ),
                    ],
                    false
                );

                // output controllers for module
                foreach (
                    $pluginList as $pluginName => $pluginClass
                ) {
                    $this->console->writeListItemLineLevel3(
                        'task_display_loaded_forms_form_class',
                        [
                            $this->console->colorize(
                                $pluginName, Color::GREEN
                            ),
                            $this->console->colorize(
                                $pluginClass, Color::BLUE
                            )
                        ],
                        false
                    );
                }
            }
        }

        return 0;
    }

}