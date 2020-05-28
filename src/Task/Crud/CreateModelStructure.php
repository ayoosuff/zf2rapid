<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Task\CreateStructure\AbstractCreateStructureTask;

/**
 * Class CreateModelStructure
 *
 * @package ZF2rapid\Task\Controller
 */
class CreateModelStructure extends AbstractCreateStructureTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->createDirectory(
            $this->params->entityDir, 'Entity'
        );

        if (!$result) {
            return 1;
        }

        $result = $this->createDirectory(
            $this->params->hydratorDir, 'Hydrator'
        );

        if (!$result) {
            return 1;
        }

        $result = $this->createDirectory(
            $this->params->hydratorStrategyDir, 'Hydrator Strategy'
        );

        if (!$result) {
            return 1;
        }

        $result = $this->createDirectory(
            $this->params->tableGatewayDir, 'TableGateway'
        );

        if (!$result) {
            return 1;
        }

        $result = $this->createDirectory(
            $this->params->storageDir, 'Storage'
        );

        if (!$result) {
            return 1;
        }

        $result = $this->createDirectory(
            $this->params->repositoryDir, 'Repository'
        );

        if (!$result) {
            return 1;
        }

        $result = $this->createDirectory(
            $this->params->inputFilterDir, 'Input Filter'
        );

        if (!$result) {
            return 1;
        }

        return 0;
    }

}