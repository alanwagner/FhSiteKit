<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgPattern\Form;

use Zend\Form\Form;

/**
 * The Pattern form class
 */
class PatternForm extends Form
{
    /**
     * Constructor
     * @param string $name
     */
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('pattern');

        $this->add(array(
                'name' => 'id',
                'type' => 'Hidden',
        ));
        $this->add(array(
                'name' => 'name',
                'type' => 'Text',
                'options' => array(
                    'label'    => 'Pattern Name *',
                ),
            ),
            array(
                'priority' => 5000,
        ));
        $this->add(array(
                'name' => 'content',
                'type' => 'TextArea',
                'options' => array(
                    'label' => 'Content *',
                ),
                'attributes' => array(
                    'rows' => '20',
                    'cols' => '80',
                ),
            ),
            array(
                'priority' => 4000,
        ));
        $this->add(array(
                'name' => 'description',
                'type' => 'TextArea',
                'options' => array(
                    'label' => 'Description',
                ),
                'attributes' => array(
                    'rows' => '10',
                    'cols' => '40',
                ),
            ),
            array(
                'priority' => 3000,
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
