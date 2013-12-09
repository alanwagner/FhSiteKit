<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskVitamin\Core;

/**
 * Generic vitamin consumer class
 */
class BaseConsumer
{
    /**
     * Internal component pointer
     * @var mixed
     */
    protected $component = null;

    /**
     * Static shared component registry
     * @var array
     */
    protected static $componentRegistry = array();

    public function __construct()
    {
        $class = get_class($this);
        if (! empty(static::$componentRegistry[$class])) {
            $this->component = clone static::$componentRegistry[$class];
        }
    }

    /**
     * Add a component to internal vitamin chain
     * @param mixed $componentToAdd
     */
    public function addComponent($componentToAdd)
    {
        if (! empty($this->component)) {
            $this->component->addComponent($componentToAdd);
        } else {
            $this->component = $componentToAdd;
        }
    }

    /**
     * Add a component pointer to internal vitamin chain
     * @param mixed $componentToAdd
     * @return BaseForm
     */
    public function registerComponent($componentToAdd)
    {
        $class = get_class($this);
        if (! empty(static::$componentRegistry[$class])) {
            $component = static::$componentRegistry[$class];
            $component->addComponent($componentToAdd);
        } else {
            static::$componentRegistry[$class] = $componentToAdd;
        }

        //  once the component's been put on the registry chain,
        //    put it on the live chain too

        $this->addComponent($componentToAdd);

        //  fluid interface

        return $this;
    }

    /**
     * Reset internal component registry and chain
     */
    public static function clearComponents()
    {
        $class = get_class($this);
        if (! empty(static::$componentRegistry[$class])) {
            static::$componentRegistry[$class] = null;
        }
        $this->component = null;
    }

    /**
     * Clone component when cloning holder
     */
    public function __clone()
    {
        if (! empty($this->component)) {
            $this->component = clone $this->component;
        }
    }
}
