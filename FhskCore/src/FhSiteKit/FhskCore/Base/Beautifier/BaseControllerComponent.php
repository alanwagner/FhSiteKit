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
 * Generic controller beautifier component class
 */
class BaseControllerComponent
{
    /**
     * Internal component pointer
     * @var BaseControllerComponent
     */
    protected $component = null;

    /**
     * Get array of partials from self and components, to build in view
     * @return array
     */
    public function getPartialTemplates()
    {
        $templates = $this->declarePartialTemplates();
        if (! empty($this->component)) {
            $templates = array_merge_recursive($templates, $this->component->getPartialTemplates());
        }

        return $templates;
    }

    /**
     * Declare array of partials to build in view
     *
     * @return array
     */
    public static function declarePartialTemplates()
    {
        return array();
    }

    /**
     * Add a component to internal beautifier chain
     * @param BaseControllerComponent $componentToAdd
     */
    public function addComponent(BaseControllerComponent $componentToAdd)
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
