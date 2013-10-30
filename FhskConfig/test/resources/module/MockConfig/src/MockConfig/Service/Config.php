<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace MockConfig\Service;

use FhSiteKit\FhskCore\Service\StaticAggregate;
use FhSiteKit\FhskConfig\Model\ConfigTableInterface;

/**
 * Static config aggregator
 */
class Config extends StaticAggregate
{
    /**
     * Settable config array
     * @var array
     */
    protected static $config = array();

    /**
     * Get array of currently registered keys
     * @return array
     */
    public function getKeys()
    {
        return static::getAll();
    }

    /**
     * Get array of config_key => config_val pairs
     *
     * Read from database, fill non-existent values with null
     * @return array
     */
    public function getConfigArray()
    {
        if (! empty(static::$config)) {

            return static::$config;
        }

        $keys = static::getAll();
        $data = array_fill_keys($keys, null);

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
        $data = $this->getConfigArray();
        foreach ($data as $key => $val) {
            $data[$key] = static::formatValue($val);
        }

        return $data;
    }

    /**
     * Set full array of mock config data
     * @return array
     */
    public function setConfig($data)
    {
        static::$config = $data;
    }

    /**
     * Set config table
     * @param mixed $configTable
     * @return \MockConfig\Service\Config
     */
    public function setConfigTable($configTable)
    {
        return $this;
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
     * @param mixed $string
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
}
