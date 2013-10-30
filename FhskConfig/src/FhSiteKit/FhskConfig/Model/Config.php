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

use FhSiteKit\FhskCore\FhskEntity\Model\Entity;
use Zend\InputFilter\InputFilter;

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
    public static function getPropList()
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
     * Get the input filter
     * @return \Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
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
        }

        return $this->inputFilter;
    }
}
