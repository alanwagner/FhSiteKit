<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\FhskEntity\Model;

use Zend\Filter\Int;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArrayObject;

/**
 * Common model entity functions
 */
class Entity implements InputFilterAwareInterface
{
    /**
     * The entity id property
     * @var int
     */
    public $id = null;

    /**
     * Input filter
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * Get array of names of entity's public properties
     *
     * @return array
     */
    public static function getPropList()
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

        $propList = static::getPropList();

        $old = array();

        foreach ($propList as $prop) {
            $old[$prop] = $this->$prop;
            $this->$prop = (isset($input[$prop])) ?  $input[$prop] : $this->$prop;
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

        $propList = static::getPropList();

        $old = array();

        $classVars = get_class_vars(get_class($this));

        foreach ($propList as $prop) {
            $old[$prop] = $this->$prop;
            $this->$prop = (isset($input[$prop])) ? $input[$prop] : $classVars[$prop];
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
        $propList = static::getPropList();
        $copy = array();
        foreach ($propList as $prop) {
            $copy[$prop] = $this->$prop;
        }

        return $copy;
    }

    /**
     * SetInputFilter required by InputFilterAwareInterface
     * @param InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * Get the input filter
     * @return \Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
