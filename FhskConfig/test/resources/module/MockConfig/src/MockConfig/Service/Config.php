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

use FhSiteKit\FhskCore\Base\Service\StaticAggregate;
use FhSiteKit\FhskConfig\Model\Config as ConfigEntity;
use FhSiteKit\FhskConfig\Model\ConfigTableInterface;
use FhSiteKit\FhskConfig\Service\Config as ConfigService;

/**
 * Mock config service
 */
class Config extends ConfigService
{
    /**
     * Settable config array
     * @var array
     */
    protected static $config = array();

    /**
     * Set full array of mock config data
     * @return array
     */
    public function setConfig($data)
    {
        static::$config = $data;
        static::$queue = array_keys($data);
    }

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
     * Get a config entity, by key
     *
     * Returns null if key not registered
     * @param string $key
     * @return \FhSiteKit\FhskConfig\Model\Config|null
     */
    public function getConfigByKey($key)
    {
        $configArray = $this->getConfigArray();
        if (! array_key_exists($key, $configArray)) {

            return null;
        }

        $config = new ConfigEntity();
        $data = array(
            'config_key'   => $key,
            'config_value' => $configArray[$key],
        );

        if ($configArray[$key] !== null) {
            //  simulate db data
            $data['id'] = 420;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $config->exchangeArray($data);

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
        return parent::getConfigFormattedByKey($key);
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
     * Removes html formatting from string, array['config_value'], or object->config_value
     * @param mixed $data
     * @return mixed
     */
    public function unformat($data)
    {
        return parent::unformat($data);
    }

    /**
     * Is a given key registered?
     * @param string $key
     * @return boolean
     */
    public static function hasKey($key)
    {
        return parent::hasKey($key);
    }

    /**
     * Add a key or an array of keys to the list
     * @param string|array $key
     */
    public static function registerKey($key)
    {
        parent::registerKey($key);
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
        parent::formatValue($value);
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
        parent::unformatValue($value);
    }

    /**
     * Set config table
     * @param ConfigTableInterface $configTable
     * @return \MockConfig\Service\Config
     */
    public function setConfigTable(ConfigTableInterface $configTable)
    {
        return $this;
    }
}
