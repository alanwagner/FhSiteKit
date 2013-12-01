<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\Base\Service;

/**
 * Generic static aggregate class
 */
class StaticAggregate
{
    /**
     * Internal queue
     * @var array
     */
    protected static $queue = array();

    /**
     * Append an item or an array of items
     * @param array|mixed $items
     */
    public static function append($items)
    {
        if (! is_array($items)) {
            $items = array($items);
        }
        foreach ($items as $item) {
            static::$queue[] = $item;
        }
    }

    /**
     * Get internal queue
     *
     * @return array
     */
    public static function getAll()
    {
        return static::$queue;
    }

    /**
     * Reset internal queue
     */
    public static function reset()
    {
        static::$queue = array();
    }
}
