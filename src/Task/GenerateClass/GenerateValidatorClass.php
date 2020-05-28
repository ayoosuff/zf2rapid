<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateClass;

use ZF2rapid\Generator\ClassGenerator\ValidatorClassGenerator;

/**
 * Class GenerateValidatorClass
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateValidatorClass extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->generateClass(
            $this->params->validatorDir,
            $this->params->paramValidator,
            'validator',
            new ValidatorClassGenerator($this->params->config)
        );

        return $result == true ? 0 : 1;
    }
}