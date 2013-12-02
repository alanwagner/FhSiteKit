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

use Zend\Form\Form;

/**
 * Generic form beautifier subject class
 */
class BaseForm extends Form
{
    /**
     * Internal component pointer
     * @var BaseFormComponent
     */
    protected $component = null;

    /**
     * Static shared component registry
     * @var array
     */
    protected static $componentRegistry = array();

    public function __construct($name)
    {
        $class = get_class($this);
        if (! empty(static::$componentRegistry[$class])) {
            $this->component = clone static::$componentRegistry[$class];
        }

        parent::__construct($name);
    }

    /**
     * Add a component to internal beautifier chain
     * @param BaseFormComponent $componentToAdd
     */
    public function addComponent(BaseFormComponent $componentToAdd)
    {
        if (! empty($this->component)) {
            $this->component->addComponent($componentToAdd);
        } else {
            $this->component = $componentToAdd;
        }
    }

    /**
     * Add a component pointer to internal beautifier chain
     * @param string $componentToAdd
     * @return BaseSubject
     */
    public function registerComponent(BaseFormComponent $componentToAdd)
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

    /**
     *
     */
    protected function beautifyForm()
    {
        if (! empty($this->component)) {
            $elements = $this->component->getElementsArray();
            foreach ($elements as $element) {
                $this->add($element['spec'], $element['flags']);
            }
        }
    }
}
