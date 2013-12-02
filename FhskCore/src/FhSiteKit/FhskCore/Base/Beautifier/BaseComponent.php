<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\Base\Beautifier;

/**
 * Generic beautifier component class
 */
class BaseComponent
{
    /**
     * Internal component pointer
     * @var BaseComponent
     */
    protected static $component = null;

    /**
     * Add a component to internal beautifier chain
     * @param BaseComponent $component
     */
    public static function addComponent(BaseComponent $component)
    {
        if (static::$component === null) {
            static::$component = $component;
        } else {
            $beautifier = static::$component;
            $beautifier->addComponent($component);
        }
    }

    /**
     * Reset internal component pointer
     */
    public static function clearComponents()
    {
        static::$component = null;
    }

    /**
     * Try component in order to get inaccessible properties
     * @param string $name
     * @throws \Exception
     * @return mixed
     */
    public function __get($name)
    {
        if (static::$component !== null) {

            return static::$component->$name;
        }

        throw new \Exception(sprintf('Property "%s" not found among beautifier components', $name));
    }

    /**
     * Try component in order to set inaccessible properties
     * @param string $name
     * @param mixed  $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if (static::$component !== null) {
            static::$component->$name = $value;
        } else {
            throw new \Exception(sprintf('Property "%s" not found among beautifier components', $name));
        }
    }
}
