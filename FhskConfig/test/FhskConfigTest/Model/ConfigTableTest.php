<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskConfigTest\Model;

use FhSiteKit\FhskConfig\Model\ConfigTable;
use FhSiteKit\FhskConfig\Model\Config;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the ConfigTable class
 */
class ConfigTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllConfigs()
    {
        $resultSet = new ResultSet();
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with()
            ->will($this->returnValue($resultSet));

        $configTable = new ConfigTable($mockTableGateway);

        $this->assertSame($resultSet, $configTable->fetchAll());
    }

    public function testCanRetrieveAConfigByItsId()
    {
        $config = $this->getConfigWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Config());
        $resultSet->initialize(array($config));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 420))
            ->will($this->returnValue($resultSet));

        $configTable = new ConfigTable($mockTableGateway);

        $this->assertSame($config, $configTable->getConfig(420));
    }

    public function testCanRetrieveAConfigByItsKey()
    {
        $config = $this->getConfigWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Config());
        $resultSet->initialize(array($config));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('config_key' => 'Foo'))
            ->will($this->returnValue($resultSet));

        $configTable = new ConfigTable($mockTableGateway);

        $this->assertSame($config, $configTable->getConfigByKey('Foo'));
    }

    public function testTryingToRetrieveAConfigByNonexistentKeyReturnsNull()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Config());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('config_key' => 'Foo'))
            ->will($this->returnValue($resultSet));

        $configTable = new ConfigTable($mockTableGateway);

        $this->assertSame(null, $configTable->getConfigByKey('Foo'));
    }

    public function testCanDeleteAConfigByItsId()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('delete'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('delete')
            ->with(array('id' => 420));

        $configTable = new ConfigTable($mockTableGateway);
        $configTable->deleteConfig(420);
    }

    public function testSaveConfigWillInsertNewConfigsIfTheyDontAlreadyHaveAnId()
    {
        $configData = $this->getDataArray();
        $created = $configData['created_at'];
        unset($configData['id']);
        unset($configData['created_at']);

        $config     = new Config();
        $config->exchangeArray($configData);
        $configData['created_at'] = $created;

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('insert', 'select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($configData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Config());
        $resultSet->initialize(array($this->getConfigWithData()));
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->will($this->returnValue($resultSet));;

        $configTable = new ConfigTable($mockTableGateway);
        $configTable->saveConfig($config);
    }

    public function testSaveConfigWillUpdateExistingConfigsIfTheyAlreadyHaveAnId()
    {
        $config = $this->getConfigWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Config());
        $resultSet->initialize(array($config));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select', 'update'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->any())
            ->method('select')
            ->with(array('id' => 420))
            ->will($this->returnValue($resultSet));

        $configData = $this->getDataArray();
        unset($configData['id']);
        unset($configData['created_at']);

        $mockTableGateway->expects($this->once())
            ->method('update')
            ->with($configData, array('id' => 420));

        $configTable = new ConfigTable($mockTableGateway);
        $configTable->saveConfig($config);
    }

    public function testExceptionIsThrownWhenGettingNonExistentConfig()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Config());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 420))
            ->will($this->returnValue($resultSet));

        $configTable = new ConfigTable($mockTableGateway);

        try {
            $configTable->getConfig(420);
        }
        catch (\Exception $e) {
            $this->assertSame('Could not find row 420', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }

    public function testGetTableGateway()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );

        $configTable = new ConfigTable($mockTableGateway);

        $this->assertSame($mockTableGateway, $configTable->getTableGateway());
    }

    /**
     * Get Config entity initialized with standard data
     * @return FhSiteKit\FhskConfig\Model\Config
     */
    protected function getConfigWithData()
    {
        $config = new Config();
        $data  = $this->getDataArray();
        $config->exchangeArray($data);

        return $config;
    }

    /**
     * Get standard data as array
     * @return array
     */
    protected function getDataArray()
    {
        return array(
            'id'           => 420,
            'config_key'   => 'Foo',
            'config_value' => "bar",
            'created_at'   => date('Y-m-d H:i:s'),
        );
    }
}
