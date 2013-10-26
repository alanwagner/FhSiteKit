<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgTemplate\Form;

use NdgPattern\Model\PatternTableInterface;
use Zend\Form\Form;

/**
 * The Template form class
 */
class TemplateForm extends Form
{
    /**
     *
     * @var PatternTableInterface
     */
    protected $patternTable;

    /**
     * Constructor
     * @param PatternTableInterface $patternTable
     * @param string $name
     */
    public function __construct(PatternTableInterface $patternTable)
    {
        parent::__construct('template');

        $this->patternTable = $patternTable;

        $this->add(array(
                'name' => 'id',
                'type' => 'Hidden',
        ));
        $this->add(array(
                'name' => 'name',
                'type' => 'Text',
                'options' => array(
                    'label'    => 'Template Name *',
                ),
                'attributes' => array(
                    'size' => '35',
                ),
            ),
            array(
                'priority' => 5000,
        ));
        $this->add(array(
                'name' => 'pattern_id',
                'type' => 'Select',
                'options' => array(
                    'label' => 'Pattern *',
                    'empty_option' => 'Select...',
                    'value_options' => $this->getPatternOptions(),
                ),
            ),
            array(
                'priority' => 4000,
        ));
        $this->add(array(
                'name' => 'instance_name',
                'type' => 'Text',
                'options' => array(
                    'label'    => 'Instance Name',
                ),
                'attributes' => array(
                    'size' => '35',
                ),
            ),
            array(
                'priority' => 3500,
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

    /**
     * Get value_options for pattern_id
     * @return array Array of id => name pairs
     */
    protected function getPatternOptions()
    {
        $options = array();
        $patterns = $this->patternTable->fetchByIsArchived(0);
        foreach ($patterns as $pattern) {
            $options[$pattern->id] = $pattern->name;
        }

        return $options;
    }
}
