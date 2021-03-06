<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2016 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator\ClassGenerator;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Filter\StaticFilter;

/**
 * Class TableGatewayClassGenerator
 *
 * @package ZF2rapid\Generator\ClassGenerator
 */
class TableGatewayClassGenerator extends ClassGenerator
    implements ClassGeneratorInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var array
     */
    protected $loadedTables;

    /**
     * @param array  $config
     * @param string $tableName
     * @param array  $loadedTables
     */
    public function __construct(
        array $config = [], $tableName, array $loadedTables = []
    ) {
        // set config data
        $this->config       = $config;
        $this->tableName    = $tableName;
        $this->loadedTables = $loadedTables;

        // call parent constructor
        parent::__construct();
    }

    /**
     * Build the class
     *
     * @param string $className
     * @param string $moduleName
     */
    public function build($className, $moduleName)
    {
        // set name and namespace
        $this->setName($className);
        $this->setNamespaceName(
            $moduleName . '\\' . $this->config['namespaceTableGateway']
        );

        // add used namespaces and extended classes
        $this->addUse('ZFrapidDomain\TableGateway\AbstractTableGateway');
        $this->setExtendedClass('AbstractTableGateway');
        $this->addClassDocBlock($className, $moduleName);

        // add selectWith() method if needed
        $this->addSelectWithMethod();
    }

    /**
     * Add a class doc block
     *
     * @param string $className
     * @param string $moduleName
     */
    protected function addClassDocBlock($className, $moduleName)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Provides the ' . $className . ' table gateway for the '
                    . $moduleName . ' Module',
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Add a selectWith() method if table has an external dependency
     *
     * @return MethodGenerator
     */
    protected function addSelectWithMethod()
    {
        $foreignKeys = $this->loadedTables[$this->tableName]['foreignKeys'];

        if (empty($foreignKeys)) {
            return true;
        }

        $body = [];

        /** @var ConstraintObject $foreignKey */
        foreach ($foreignKeys as $foreignKey) {
            $refTableName = $foreignKey->getReferencedTableName();

            $refTableColumns = $this->loadedTables[$refTableName]['columns'];

            $body[] = '$select->join(';
            $body[] = '    \'' . $refTableName . '\',';
            $body[] = '    \'' . $this->tableName . '.'
                . $foreignKey->getColumns()[0] . ' = ' . $refTableName . '.'
                . $foreignKey->getReferencedColumns()[0] . '\',';
            $body[] = '    [';

            
            /** @var ColumnObject $column */
            foreach ($refTableColumns as $column) {
                $body[] = '        \'' . $refTableName . '.' . $column->getName(
                    ) . '\' => \'' . $column->getName() . '\',';
            }

            $body[] = '    ]';
            $body[] = ');';
            $body[] = '';
        }

        $body[] = 'return parent::selectWith($select);';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $this->addUse('Zend\Db\ResultSet\ResultSetInterface');
        $this->addUse('Zend\Db\Sql\Select');

        $selectMethod = new MethodGenerator('selectWith');
        $selectMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $selectMethod->setParameter(
            new ParameterGenerator('select', 'Select')
        );
        $selectMethod->setDocBlock(
            new DocBlockGenerator(
                'Add join tables',
                null,
                [
                    [
                        'name'        => 'param',
                        'description' => 'Select $select',
                    ],
                    [
                        'name'        => 'return',
                        'description' => 'ResultSetInterface',
                    ],
                ]
            )
        );


        $selectMethod->setBody($body);

        $this->addMethodFromGenerator($selectMethod);

        return true;
    }

    /**
     * Filter underscore to camel case
     *
     * @param string $text
     *
     * @return string
     */
    public function filterUnderscoreToCamelCase($text)
    {
        $text = StaticFilter::execute($text, 'Word\UnderscoreToCamelCase');

        return $text;
    }
}
