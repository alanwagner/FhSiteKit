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

use FhSiteKit\FhskCore\Controller\BaseController as FhskBaseController;

/**
 * Generic controller beautifier subject class
 */
class BaseController extends FhskBaseController
{
    /**
     * Internal component pointer
     * @var BaseControllerComponent
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
     * Add a component pointer to internal beautifier chain
     * @param BaseControllerComponent $componentToAdd
     * @return BaseController
     */
    public function registerComponent(BaseControllerComponent $componentToAdd)
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
     * Add list of partial templates to build in view, from beautifier components
     */
    protected function addAdditionalViewData()
    {
        parent::addAdditionalViewData();

        if (! empty($this->component)) {
            $partials = $this->component->getPartialTemplates();
            $action = $this->params()->fromRoute('action');
            if (isset($partials[$action])) {
                $this->viewData['partials'] = $partials[$action];
            }
        }
    }
}
