<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskConfig\Service;

use FhSiteKit\FhskCore\Base\Service\StaticAggregate;
use FhSiteKit\FhskConfig\Model\ConfigTableInterface;

/**
 * Centralized config service
 */
class Config extends StaticAggregate
{
    /**
     * The config table gateway
     * @var ConfigTableInterface
     */
    protected $configTable;

    /**
     * Get array of currently registered keys
     * @return array
     */
    public function getKeys()
    {
        return static::getAll();
    }

    /**
     * Get a config entity, by key
     *
     * Returns null if key not registered
     * @param string $key
     * @return \FhSiteKit\FhskConfig\Model\Config|null
     */
    public function getConfigByKey($key)
    {
        $config = $this->configTable->getConfigByKey($key);

        if ($config === null && static::hasKey($key)) {
            //  key is registered but not in db
            //  Get the Config entity
            //    don't assume it's from this module, or hard-code the class
            $config = $this->configTable
                           ->getTableGateway()
                           ->getResultSetPrototype()
                           ->getArrayObjectPrototype();

            $config->exchangeArray(array('config_key' => $key));
        }

        return $config;
    }

    /**
     * Get a config entity with formatted config_value, by key
     *
     * Returns null if key not registered
     * @param string $key
     * @return \FhSiteKit\FhskConfig\Model\Config|null
     */
    public function getConfigFormattedByKey($key)
    {
        $config = $this->getConfigByKey($key);
        if ($config !== null) {
            $config->config_value = static::formatValue($config->config_value);
        }

        return $config;
    }

    /**
     * Get array of config_key => config_val pairs
     *
     * Read from database, fill non-existent values with null
     * @return array
     */
    public function getConfigArray()
    {
        $keys = static::getAll();
        $data = array_fill_keys($keys, null);
        $rows = $this->configTable->fetchAll();

        foreach ($rows as $config) {
            if (array_key_exists($config->config_key, $data)) {
                $data[$config->config_key] = $config->config_value;
            }
        }

        return $data;
    }

    /**
     * Get array of config_key => formatted config_val pairs
     *
     * Read from database, fill non-existent values with empty string (formatted null)
     * @return array
     */
    public function getConfigArrayFormatted()
    {
        $keys = static::getAll();
        $data = array_fill_keys($keys, static::formatValue(null));
        $rows = $this->configTable->fetchAll();

        foreach ($rows as $config) {
            if (array_key_exists($config->config_key, $data)) {
                $data[$config->config_key] = static::formatValue($config->config_value);
            }
        }

        return $data;
    }

    /**
     * Removes html formatting from string, array['config_value'], or object->config_value
     * @param mixed $data
     * @return mixed
     */
    public function unformat($data)
    {
        if (is_string($data)) {
            $data = static::unformatValue($data);
        }
        if (is_array($data) && array_key_exists('config_value', $data)) {
            $data['config_value'] = static::unformatValue($data['config_value']);
        }
        if (is_object($data) && array_key_exists('config_value', get_object_vars($data))) {
            $data->config_value = static::unformatValue($data->config_value);
        }

        return $data;
    }

    /**
     * Is a given key registered?
     * @param string $key
     * @return boolean
     */
    public static function hasKey($key)
    {
        $keys = static::getAll();

        return in_array($key, $keys);
    }

    /**
     * Add a key or an array of keys to the list
     * @param string|array $key
     */
    public static function registerKey($key)
    {
        static::append($key);
    }

    /**
     * Format a value for display in html
     *
     * Turns empty string into a literal double quote ""
     * Turns NULL into empty string
     *
     * @param mixed $value
     * @return string
     */
    public static function formatValue($value)
    {
        if ($value === '') {
            $value = '""';
        }
        if ($value === null) {
            $value = '';
        }

        return $value;
    }

    /**
     * Prepare html-formatted value for recording in database
     *
     * Turns empty string into NULL
     * Turns a literal double quote "" into empty string
     *
     * @param mixed $value
     * @return string
     */
    public static function unformatValue($value)
    {
        if ($value === '') {
            $value = null;
        }
        if ($value === '""') {
            $value = '';
        }

        return $value;
    }

    /**
     * Set config table
     * @param ConfigTableInterface $configTable
     * @return \FhSiteKit\FhskConfig\Service\Config
     */
    public function setConfigTable(ConfigTableInterface $configTable)
    {
        $this->configTable = $configTable;

        return $this;
    }
}
