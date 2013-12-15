<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskEmail\Form;

use Zend\Form\Form;

/**
 * The Email form class
 */
class EmailForm extends Form
{
    /**
     * Constructor
     * @param string $name
     */
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('email');

        $this->add(array(
                'name' => 'id',
                'type' => 'Hidden',
        ));
        $this->add(array(
                'name' => 'key',
                'type' => 'Hidden',
        ));
        $this->add(array(
                'name' => 'from_name',
                'type' => 'Text',
                'options' => array(
                    'label' => 'From Name *',
                ),
                'attributes' => array(
                    'size' => '40',
                ),
            ),
            array(
                'priority' => 4500,
        ));
        $this->add(array(
                'name' => 'from_address',
                'type' => 'Text',
                'options' => array(
                    'label' => 'From Address *',
                ),
                'attributes' => array(
                    'size' => '40',
                ),
            ),
            array(
                'priority' => 4000,
        ));
        $this->add(array(
                'name' => 'subject_template',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Subject Template *',
                ),
                'attributes' => array(
                    'size' => '40',
                ),
            ),
            array(
                'priority' => 3500,
        ));
        $this->add(array(
                'name' => 'body_template',
                'type' => 'TextArea',
                'options' => array(
                    'label' => 'Body Template *',
                ),
                'attributes' => array(
                    'rows' => '15',
                    'cols' => '80',
                ),
            ),
            array(
                'priority' => 3000,
        ));
        $this->add(array(
                'name' => 'schedule',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Time to Send each day',
                ),
            ),
            array(
                'priority' => 2500,
        ));
        $this->add(array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Submit',
                    'id' => 'submitbutton',
                ),
            ),
            array(
                'priority' => 1000,
        ));
    }
}
