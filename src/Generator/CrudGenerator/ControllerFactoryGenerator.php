<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator\CrudGenerator;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlock\Tag\ParamTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

/**
 * Class ControllerFactoryGenerator
 *
 * @package ZF2rapid\Generator\CrudGenerator
 */
class ControllerFactoryGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param string $controllerName
     * @param string $moduleName
     * @param string $entityModule
     * @param string $entityClass
     * @param array  $config
     */
    public function __construct(
        $controllerName, $moduleName, $entityModule, $entityClass, array $config = []
    ) {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct(
            $controllerName . 'Factory',
            $moduleName . '\\' . $this->config['namespaceController']
        );

        // prepare repository params
        $repositoryClass     = str_replace('Entity', '', $entityClass) . 'Repository';
        $repositoryNamespace = $entityModule . '\\' . $this->config['namespaceRepository'] . '\\' . $repositoryClass;

        // prepare form params
        if (in_array($controllerName, ['CreateController', 'UpdateController'])) {
            $formClass     = str_replace('Entity', '', $entityClass) . 'DataForm';
            $formNamespace = $moduleName . '\\' . $this->config['namespaceForm'] . '\\' . $formClass;

            $this->addUse($formNamespace);
        } elseif (in_array($controllerName, ['DeleteController'])) {
            $formClass     = str_replace('Entity', '', $entityClass) . 'DeleteForm';
            $formNamespace = $moduleName . '\\' . $this->config['namespaceForm'] . '\\' . $formClass;

            $this->addUse($formNamespace);
        }

        // add used namespaces and extended classes
        $this->addUse($repositoryNamespace);
        $this->addUse('Zend\ServiceManager\FactoryInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $this->setImplementedInterfaces(['FactoryInterface']);

        // add methods
        $this->addCreateServiceMethod($controllerName, $moduleName, $entityModule, $entityClass);
        $this->addClassDocBlock($controllerName);
    }

    /**
     * Add a class doc block
     *
     * @param string $className
     */
    protected function addClassDocBlock($className)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Creates an instance of ' . $className,
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Generate the create service method
     *
     * @param string $className
     * @param        $moduleName
     * @param string $entityModule
     * @param        $entityClass
     */
    protected function addCreateServiceMethod($className, $moduleName, $entityModule, $entityClass)
    {
        // prepare repository params
        $repositoryClass   = str_replace('Entity', '', $entityClass) . 'Repository';
        $repositoryParam   = lcfirst($repositoryClass);
        $repositoryService = $entityModule . '\\' . $this->config['namespaceRepository'] . '\\' . str_replace('Entity', '', $entityClass);

        // prepare form params
        if (in_array($className, ['CreateController', 'UpdateController'])) {
            $formClass   = str_replace('Entity', '', $entityClass) . 'DataForm';
            $formParam   = lcfirst($formClass);
            $formService = $moduleName . '\\Data\\Form';
        } elseif (in_array($className, ['DeleteController'])) {
            $formClass   = str_replace('Entity', '', $entityClass) . 'DeleteForm';
            $formParam   = lcfirst($formClass);
            $formService = $moduleName . '\\Delete\\Form';
        } else {
            $formClass   = null;
            $formParam   = null;
            $formService = null;
        }

        // set action body
        $body   = [];
        $body[] = '$serviceLocator = $controllerManager->getServiceLocator();';
        $body[] = '';

        if ($formClass) {
            $body[] = '/** @var ServiceLocatorInterface $formElementManager */';
            $body[] = '$formElementManager = $serviceLocator->get(\'FormElementManager\');';
            $body[] = '';
        }

        $body[] = '/** @var ' . $repositoryClass . ' $' . $repositoryParam . ' */';
        $body[] = '$' . $repositoryParam . ' = $serviceLocator->get(\'' . $repositoryService . '\');';
        $body[] = '';

        if ($formClass) {
            $body[] = '/** @var ' . $formClass . ' $' . $formParam . ' */';
            $body[] = '$' . $formParam . ' = $formElementManager->get(\'' . $formService . '\');';
            $body[] = '';
        }

        $body[] = '$instance = new ' . $className . '();';
        $body[] = '$instance->set' . $repositoryClass . '($' . $repositoryParam . ');';

        if ($formClass) {
            $body[] = '$instance->set' . $formClass . '($' . $formParam . ');';
        }

        $body[] = '';
        $body[] = 'return $instance;';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('createService');
        $method->setBody($body);
        $method->setParameters(
            [
                new ParameterGenerator(
                    'controllerManager', 'ServiceLocatorInterface'
                ),
            ]
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Create service',
                    null,
                    [
                        new ParamTag(
                            'controllerManager',
                            [
                                'ServiceLocatorInterface',
                                'ServiceLocatorAwareInterface',
                            ]
                        ),
                        new ReturnTag([$className]),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }
}