<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskVitamin\Model;

use FhSiteKit\FhskVitamin\Core\BaseComponent;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArrayObject;

/**
 * Vitamin component for Entity
 */
class EntityComponent extends BaseComponent implements InputFilterAwareInterface
{
    /**
     * Input filter
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * Get array of names of entity's and vitamin components' public properties
     *
     * @return array
     */
    public function getPropList()
    {
        $props = static::declarePropList();
        if (! empty($this->component)) {
            $props = $this->component->enhancePropList($props);
        }

        return $props;
    }

    /**
     * Declare array of names of entity's public properties
     *
     * @return array
     */
    public static function declarePropList()
    {
        return array();
    }

    /**
     * Enhance array of names of holder's public properties
     * @param array $props
     * @return array
     */
    public function enhancePropList($props)
    {
        $props = array_merge($props, $this->getPropList());

        return $props;
    }

    /**
     * Get array of data from entity and vitamin components for save function to insert or update
     *
     * @return array
     */
    public function getDataToSave()
    {
        $data = $this->provideDataToSave();
        if (! empty($this->component)) {
            $data = $this->component->enhanceDataToSave($data);
        }

        return $data;
    }

    /**
     * Provide array of data for save function to insert or update
     * @param array $data
     * @return array
     */
    public function provideDataToSave()
    {
        $data = array();

        return $data;
    }

    /**
     * Enhance the holder's data to save
     * @param array $data
     * @return array
     */
    public function enhanceDataToSave($data)
    {
        $data = array_merge($data, $this->getDataToSave());

        return $data;
    }

    /**
     * Get array of entity's and vitamin components' class vars
     *
     * @return array
     */
    public function getClassVars()
    {
        $vars = get_class_vars(get_class($this));
        if (! empty($this->component)) {
            $vars = $this->component->enhanceClassVars($vars);
        }

        return $vars;
    }

    /**
     * Enhance the holder's class vars
     * @param array $vars
     * @return array
     */
    public function enhanceClassVars($vars)
    {
        $data = array_merge($vars, $this->getClassVars());

        return $vars;
    }

    /**
     * Get the input filter
     * @return \Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        //  Will modify $this->inputFilter
        //    if that has been set by wrapper call to beautifyInputFilter()
        $inputFilter = $this->declareInputFilter();

        if (! empty($this->component)) {
            $inputFilter = $this->component->enhanceInputFilter($inputFilter);
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * Set the input filter, or modify the existing one
     * @return \Zend\InputFilter\InputFilter
     */
    public function declareInputFilter()
    {
        if (empty($this->inputFilter)) {
            $this->inputFilter = new InputFilter();
        }
        return $this->inputFilter;
    }

    /**
     * Enhance the holder's input filter
     * @param \Zend\InputFilter\InputFilter $inputFilter
     * @return \Zend\InputFilter\InputFilter
     */
    public function enhanceInputFilter($inputFilter)
    {
        $this->inputFilter = $inputFilter;

        return $this->getInputFilter();
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
}
