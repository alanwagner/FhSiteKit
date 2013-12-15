<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskEmail\Model;

use FhSiteKit\FhskCore\Model\Entity;
use Zend\InputFilter\InputFilter;

/**
 * The Email entity
 */
class Email extends Entity
{
    /**
     * Database row id
     * @var int
     */
    public $id = null;

    /**
     * Email key
     * @var string
     */
    public $key = '';

    /**
     * Subject template
     * @var string
     */
    public $subject_template = null;

    /**
     * Body template
     * @var string
     */
    public $body_template = null;

    /**
     * From name
     * @var string
     */
    public $from_name = null;

    /**
     * From address
     * @var string
     */
    public $from_address = null;

    /**
     * Schedule
     *
     * Time of day to send email
     *
     * @var string
     */
    public $schedule = null;

    /**
     * Email row created at
     * @var string
     */
    public $created_at = null;

    /**
     * Email row updated at
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
            'key',
            'subject_template',
            'body_template',
            'from_name',
            'from_address',
            'schedule',
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
            'key'              => $this->key,
            'subject_template' => $this->subject_template,
            'body_template'    => $this->body_template,
            'from_name'        => $this->from_name,
            'from_address'     => $this->from_address,
            'schedule'         => $this->schedule,
        );

        return $data;
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
        $inputFilter = $this->inputFilter;

        $inputFilter->add(array(
            'name'     => 'id',
            'required' => true,
            'filters'  => array(
                array('name' => 'Int'),
            ),
        ));

        $inputFilter->add(array(
            'name'     => 'key',
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
            'name'     => 'subject_template',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $inputFilter->add(array(
            'name'     => 'body_template',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $inputFilter->add(array(
            'name'     => 'from_name',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $inputFilter->add(array(
            'name'     => 'from_address',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $inputFilter->add(array(
            'name'     => 'schedule',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
}
