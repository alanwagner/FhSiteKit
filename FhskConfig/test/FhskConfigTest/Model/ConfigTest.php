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

use FhSiteKit\FhskConfig\Model\Config;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the Config entity
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testConfigInitialState()
    {
        $config = new Config();

        $this->assertNull($config->id);
        $this->assertEquals('', $config->config_key);
        $this->assertNull($config->config_value);
        $this->assertNull($config->created_at);
        $this->assertNull($config->updated_at);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $config = new Config();
        $data  = $this->getDataArray();
        $config->exchangeArray($data);

        $this->assertSame($data['id'], $config->id);
        $this->assertSame($data['config_key'], $config->config_key);
        $this->assertSame($data['config_value'], $config->config_value);
        $this->assertSame($data['created_at'], $config->created_at);
        $this->assertSame($data['updated_at'], $config->updated_at);
    }

    public function testPopulateSetsPropertiesCorrectly()
    {
        $config = new Config();
        $data  = $this->getDataArray();
        $config->populate($data);

        $this->assertSame($data['id'], $config->id);
        $this->assertSame($data['config_key'], $config->config_key);
        $this->assertSame($data['config_value'], $config->config_value);
        $this->assertSame($data['created_at'], $config->created_at);
        $this->assertSame($data['updated_at'], $config->updated_at);
    }

    public function testExchangeArrayLeavesPropertiesAloneIfKeysAreNotPresent()
    {
        $config = $this->getConfigWithData();
        $copy = $config->getArrayCopy();
        $config->exchangeArray(array());

        $this->assertSame($copy['id'], $config->id);
        $this->assertSame($copy['config_key'], $config->config_key);
        $this->assertSame($copy['config_value'], $config->config_value);
        $this->assertSame($copy['created_at'], $config->created_at);
        $this->assertSame($copy['updated_at'], $config->updated_at);
    }

    public function testPopulateSetsPropertiesToDefaultIfKeysAreNotPresent()
    {
        $config = $this->getConfigWithData();
        $config->populate(array());

        $this->assertNull($config->id);
        $this->assertEquals('', $config->config_key);
        $this->assertNull($config->config_value);
        $this->assertNull($config->created_at);
        $this->assertNull($config->updated_at);
    }

    public function testExchangeArrayReturnsExistingValues()
    {
        $config = $this->getConfigWithData();
        $copyArray = $config->getArrayCopy();
        $old = $config->exchangeArray(array());

        $this->assertSame($copyArray['id'], $old['id']);
        $this->assertSame($copyArray['config_key'], $old['config_key']);
        $this->assertSame($copyArray['config_value'], $old['config_value']);
        $this->assertSame($copyArray['created_at'], $old['created_at']);
        $this->assertSame($copyArray['updated_at'], $old['updated_at']);
    }

    public function testPopulateReturnsExistingValues()
    {
        $config = $this->getConfigWithData();
        $copyArray = $config->getArrayCopy();
        $old = $config->populate(array());

        $this->assertSame($copyArray['id'], $old['id']);
        $this->assertSame($copyArray['config_key'], $old['config_key']);
        $this->assertSame($copyArray['config_value'], $old['config_value']);
        $this->assertSame($copyArray['created_at'], $old['created_at']);
        $this->assertSame($copyArray['updated_at'], $old['updated_at']);
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $config = $this->getConfigWithData();
        $data  = $this->getDataArray();
        $copyArray = $config->getArrayCopy();

        $this->assertSame($data['id'], $copyArray['id']);
        $this->assertSame($data['config_key'], $copyArray['config_key']);
        $this->assertSame($data['config_value'], $copyArray['config_value']);
        $this->assertSame($data['created_at'], $copyArray['created_at']);
        $this->assertSame($data['updated_at'], $copyArray['updated_at']);
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $config = new Config();

        $inputFilter = $config->getInputFilter();

        $this->assertSame(3, $inputFilter->count());
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('config_key'));
        $this->assertTrue($inputFilter->has('config_value'));
    }

    /**
     * Get Config entity initialized with standard data
     * @return Ndg\NdgConfig\Model\Config
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
            'id'          => 420,
            'config_key' => 'Foo',
            'config_value' => 'bar',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
    }
}
