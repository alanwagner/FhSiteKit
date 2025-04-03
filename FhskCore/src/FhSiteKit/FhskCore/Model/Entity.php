<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\Model;

use FhSiteKit\FhskVitamin\Model\EntityComponent;
use Laminas\Stdlib\ArrayObject;

/**
 * Common model entity functions
 */
class Entity extends EntityComponent
{
    /**
     * The entity id property
     * @var int
     */
    public $id = null;

    /**
     * Declare array of names of entity's public properties
     *
     * @return array
     */
    public static function declarePropList()
    {
        return array('id');
    }

    /**
     * Exchange the current array with another array or object.
     *
     * If keys are not present in $input, Entity property retains its value
     * Intended for use with forms, so they don't need form elements for every entity property
     *
     * Use populate() to clear out properties not present in $input
     *
     * @param  array|object $input
     * @return array        Returns the old array
     * @see ArrayObject::exchangeArray()
     */
    public function exchangeArray($input)
    {
        // handle arrayobject, iterators and the like:
        if (is_object($input) && ($input instanceof ArrayObject || $input instanceof \ArrayObject)) {
            $input = $input->getArrayCopy();
        }
        if (!is_array($input)) {
            $input = (array) $input;
        }

        $propList = $this->getPropList();

        $old = array();

        foreach ($propList as $prop) {
            $old[$prop] = $this->$prop;
            $this->$prop = (array_key_exists($prop, $input)) ?  $input[$prop] : $this->$prop;
        }

        return $old;
    }

    /**
     * Exchange the current array with another array or object.
     *
     * If keys are not present in $input, Entity property is set entity default
     * Use exchangeArray() to preserve properties not present in $input
     *
     * @param  array|object $input
     * @return array        Returns the old array
     */
    public function populate($input)
    {
        // handle arrayobject, iterators and the like:
        if (is_object($input) && ($input instanceof ArrayObject || $input instanceof \ArrayObject)) {
            $input = $input->getArrayCopy();
        }
        if (!is_array($input)) {
            $input = (array) $input;
        }

        $propList = $this->getPropList();

        $old = array();

        $classVars = $this->getClassVars();

        foreach ($propList as $prop) {
            $old[$prop] = $this->$prop;
            $this->$prop = (array_key_exists($prop, $input)) ? $input[$prop] : $classVars[$prop];
        }

        return $old;
    }

    /**
     * Get array of entity's public properties
     *
     * @return array
     */
    public function getArrayCopy()
    {
        $propList = $this->getPropList();
        $copy = array();
        foreach ($propList as $prop) {
            $copy[$prop] = $this->$prop;
        }

        return $copy;
    }
}
