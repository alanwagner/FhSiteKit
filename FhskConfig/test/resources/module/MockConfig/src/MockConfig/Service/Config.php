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
    public function getConfig()
    {
        if (! empty(static::$config)) {

            return static::$config;
        }

        $keys = static::getAll();
        $data = array_fill_keys($keys, null);

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
     * Add a key or an array of keys to the list
     * @param string|array $key
     */
    public static function registerKey($key)
    {
        static::append($key);
    }
}
