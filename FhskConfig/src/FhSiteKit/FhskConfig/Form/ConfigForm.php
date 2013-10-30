<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskConfig\Form;

use Zend\Form\Form;

/**
 * The Config form class
 */
class ConfigForm extends Form
{
    /**
     * Constructor
     * @param string $name
     */
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('config');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
                'name' => 'config_key',
                'type' => 'Hidden',
        ));
        $this->add(array(
                'name' => 'config_value',
                'type' => 'Text',
                'options' => array(
                    'label'    => 'configValue label',
                ),
            ),
            array(
                'priority' => 5000,
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
