<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskConfig\Model;

use FhSiteKit\FhskCore\Model\Entity;
use Laminas\InputFilter\InputFilter;

/**
 * The Config entity
 */
class Config extends Entity
{
    /**
     * Database row id
     * @var int
     */
    public $id = null;

    /**
     * Config key
     * @var string
     */
    public $config_key = '';

    /**
     * Config value
     * @var string
     */
    public $config_value = null;

    /**
     * Config row created at
     * @var string
     */
    public $created_at = null;

    /**
     * Config row updated at
     * @var string
     */
    public $updated_at = null;

    /**
     * Get array of names of entity's public properties
     * @return array
     */
    public static function declarePropList()
    {
        return array(
            'id',
            'config_key',
            'config_value',
            'created_at',
            'updated_at',
        );
    }

    /**
     * Provide array of data for save function to insert or update
     * @return array
     */
    public function provideDataToSave()
    {
        $data = array(
            'config_key'      => $this->config_key,
            'config_value'    => $this->config_value,
        );

        return $data;
    }

    /**
     * Set the input filter, or modify the existing one
     * @return \Laminas\InputFilter\InputFilter
     */
    public function declareInputFilter()
    {
        if (empty($this->inputFilter)) {
            $this->inputFilter = new InputFilter();
        }
        $inputFilter = $this->inputFilter;

        $inputFilter->add(array(
            'name'     => 'id',
            'required' => false,
            'filters'  => array(),
        ));

        $inputFilter->add(array(
            'name'     => 'config_key',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 255,
                    ),
                ),
            ),
        ));

        $inputFilter->add(array(
            'name'     => 'config_value',
            'required' => false,
        ));

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
}
