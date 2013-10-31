<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskConfigTest\Service;

use FhSiteKit\FhskConfig\Model\Config as ConfigEntity;
use FhSiteKit\FhskConfig\Service\Config as ConfigService;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the Config entity
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testRegisterKeyAndGetAllFunctionProperly()
    {
        ConfigService::reset();
        ConfigService::registerKey('Foo');
        ConfigService::registerKey(array('Bar', 'Baz'));
        $this->assertSame(array('Foo', 'Bar', 'Baz'), ConfigService::getAll());

        $config = new ConfigService();
        $config::reset();
        $config::registerKey('Foo');
        $config::registerKey(array('Bar', 'Baz'));

        $this->assertSame(array('Foo', 'Bar', 'Baz'), $config::getAll());
    }

    public function testGetKeysFunctionsProperly()
    {
        $config = new ConfigService();
        $config::reset();
        $this->assertSame(array(), $config->getKeys());

        $config::registerKey(array('Bar', 'Baz'));

        $this->assertSame(array('Bar', 'Baz'), $config->getKeys());
    }

    public function testGetConfigByKeyReturnsConfig()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getResultSetPrototype'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->never())
            ->method('getResultSetPrototype');

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configEntity = $this->getConfigEntityWithData();
        $configTableMock->expects($this->once())
            ->method('getConfigByKey')
            ->with('Foo')
            ->will($this->returnValue($configEntity));

        $configTableMock->expects($this->never())
            ->method('getTableGateway');

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);

        $this->assertSame($configEntity, $config->getConfigByKey('Foo'));
    }

    public function testGetConfigByKeyReturnsConfigWithNullValue()
    {
        $config = new ConfigService();
        $config::reset();
        $config->registerKey(array('Foo', 'Bar'));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new ConfigEntity());

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getResultSetPrototype'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('getResultSetPrototype')
            ->will($this->returnValue($resultSet));

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configTableMock->expects($this->once())
            ->method('getConfigByKey')
            ->with('Foo')
            ->will($this->returnValue(null));

        $configTableMock->expects($this->once())
            ->method('getTableGateway')
            ->will($this->returnValue($mockTableGateway));

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);

        $configEntity = new ConfigEntity();
        $configEntity->exchangeArray(array('config_key' => 'Foo'));

        $this->assertEquals($configEntity, $config->getConfigByKey('Foo'));
    }

    public function testGetConfigByKeyReturnsNull()
    {
        $config = new ConfigService();
        $config::reset();

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getResultSetPrototype'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->never())
            ->method('getResultSetPrototype');

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configEntity = $this->getConfigEntityWithData();
        $configTableMock->expects($this->once())
            ->method('getConfigByKey')
            ->with('Foo')
            ->will($this->returnValue(null));

        $configTableMock->expects($this->never())
            ->method('getTableGateway');

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);

        $this->assertEquals(null, $config->getConfigByKey('Foo'));
    }

    public function testGetConfigFormattedByKeyReturnsFormattedConfig()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getResultSetPrototype'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->never())
            ->method('getResultSetPrototype');

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configEntity = $this->getConfigEntityWithData();
        $configTableMock->expects($this->once())
            ->method('getConfigByKey')
            ->with('Foo')
            ->will($this->returnValue($configEntity));

        $configTableMock->expects($this->never())
            ->method('getTableGateway');

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);

        $this->assertSame($configEntity, $config->getConfigFormattedByKey('Foo'));
    }

    public function testGetConfigFormattedByKeyReturnsConfigWithQuotes()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getResultSetPrototype'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->never())
            ->method('getResultSetPrototype');

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configEntity = $this->getConfigEntityWithData();
        $configEntity->exchangeArray(array('config_value' => ''));
        $configTableMock->expects($this->once())
            ->method('getConfigByKey')
            ->with('Foo')
            ->will($this->returnValue($configEntity));

        $configTableMock->expects($this->never())
            ->method('getTableGateway');

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);

        $configEntity->exchangeArray(array('config_value' => '""'));

        $this->assertSame($configEntity, $config->getConfigFormattedByKey('Foo'));
    }

    public function testGetConfigFormattedByKeyReturnsConfigWithEmptyString()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getResultSetPrototype'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->never())
            ->method('getResultSetPrototype');

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configEntity = $this->getConfigEntityWithData();
        $configEntity->exchangeArray(array('config_value' => null));
        $configTableMock->expects($this->once())
            ->method('getConfigByKey')
            ->with('Foo')
            ->will($this->returnValue($configEntity));

        $configTableMock->expects($this->never())
            ->method('getTableGateway');

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);

        $configEntity->exchangeArray(array('config_value' => ''));

        $this->assertSame($configEntity, $config->getConfigFormattedByKey('Foo'));
    }

    public function testGetConfigFormattedByKeyReturnsNull()
    {
        $config = new ConfigService();
        $config::reset();

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('getResultSetPrototype'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->never())
            ->method('getResultSetPrototype');

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configEntity = $this->getConfigEntityWithData();
        $configTableMock->expects($this->once())
            ->method('getConfigByKey')
            ->with('Foo')
            ->will($this->returnValue(null));

        $configTableMock->expects($this->never())
            ->method('getTableGateway');

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);
        $this->assertEquals(null, $config->getConfigByKey('Foo'));
    }

    public function testGetConfigArrayFunctionsProperly()
    {
        $config = new ConfigService();
        $config::reset();
        $config::registerKey(array('Foo', 'Bar'));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new ConfigEntity());
        $resultSet->initialize(array($this->getConfigEntityWithData()));

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configEntity = $this->getConfigEntityWithData();
        $configTableMock->expects($this->once())
            ->method('fetchAll')
            ->with()
            ->will($this->returnValue($resultSet));

        $configTableMock->expects($this->never())
            ->method('getTableGateway');

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);

        $expected = array(
            'Foo' => 'bar',
            'Bar' => null,
        );
        $this->assertSame($expected, $config->getConfigArray());
    }

    public function testGetConfigArrayFormattedFunctionsProperly()
    {
        $config = new ConfigService();
        $config::reset();
        $config::registerKey(array('Foo', 'Bar', 'Buz'));

        $configEntityBuz = new ConfigEntity();
        $buzArray = array(
            'id'          => 421,
            'config_key' => 'Buz',
            'config_value' => '',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
        $configEntityBuz->exchangeArray($buzArray);
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new ConfigEntity());
        $resultSet->initialize(array($configEntityBuz, $this->getConfigEntityWithData()));

        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();

        $configEntity = $this->getConfigEntityWithData();
        $configTableMock->expects($this->once())
            ->method('fetchAll')
            ->with()
            ->will($this->returnValue($resultSet));

        $configTableMock->expects($this->never())
            ->method('getTableGateway');

        $config = new ConfigService();
        $config->setConfigTable($configTableMock);

        $expected = array(
            'Foo' => 'bar',
            'Bar' => '',
            'Buz' => '""',
        );
        $this->assertSame($expected, $config->getConfigArrayFormatted());
    }

    public function testUnformatFunctionsProperly()
    {
        $config = new ConfigService();
        $config::reset();
        $config::registerKey(array('Foo', 'Bar', 'Buz'));

        $this->assertSame('Foo', $config->unformat('Foo'));
        $this->assertSame(array('config_value' => ''), $config->unformat(array('config_value' => '""')));

        $configEntity = new ConfigEntity();
        $configEntity->config_value = '';
        $result = $config->unformat($configEntity);

        $configEntity->config_value = null;

        $this->assertSame($configEntity, $result);
    }

    public function testHasKeyFunctionsProperly()
    {
        $config = new ConfigService();
        $config::reset();
        $config::registerKey(array('Foo', 'Bar'));

        $this->assertTrue($config::hasKey('Foo'));
        $this->assertFalse($config::hasKey('Zappa'));
    }

    public function testFormatValueFunctionsProperly()
    {
        $config = new ConfigService();
        $config::reset();

        $this->assertSame('Foo', $config::formatValue('Foo'));
        $this->assertSame('""', $config::formatValue(''));
        $this->assertSame('', $config::formatValue(null));
    }

    public function testUnformatValueFunctionsProperly()
    {
        $config = new ConfigService();
        $config::reset();

        $this->assertSame('Foo', $config::unformatValue('Foo'));
        $this->assertSame('', $config::unformatValue('""'));
        $this->assertSame(null, $config::unformatValue(''));
    }

    public function testSetConfigTableFunctionsProperly()
    {
        $config = new ConfigService();
        $config::reset();
        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();
        $result = $config->setConfigTable($configTableMock);

        $this->assertSame($result, $config);
    }

    /**
     * Get Config entity initialized with standard data
     * @return Ndg\NdgConfig\Model\Config
     */
    protected function getConfigEntityWithData()
    {
        $config = new ConfigEntity();
        $data  = $this->getConfigEntityDataArray();
        $config->exchangeArray($data);

        return $config;
    }

    /**
     * Get standard data as array
     * @return array
     */
    protected function getConfigEntityDataArray()
    {
        return array(
            'id'          => 420,
            'config_key' => 'Foo',
            'config_value' => 'bar',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
    }
}
