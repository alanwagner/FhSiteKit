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
 * Generic form beautifier component class
 */
class BaseFormComponent
{
    /**
     * Internal component pointer
     * @var BaseFormComponent
     */
    protected $component = null;

    /**
     * Get array of elements specs from self and components, to add to form
     * @return array
     */
    public function getElementsArray()
    {
        $elements = $this->declareElementsArray();
        if (! empty($this->component)) {
            $elements = $elements + $this->component->getElementsArray();
        }

        return $elements;
    }

    /**
     * Declare array of elements to add to form
     *
     * @return array
     */
    public static function declareElementsArray()
    {
        return array();
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
     * Clone component when cloning holder
     */
    public function __clone()
    {
        if (! empty($this->component)) {
            $this->component = clone $this->component;
        }
    }
}
