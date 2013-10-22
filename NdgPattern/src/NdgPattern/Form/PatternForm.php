<?php
namespace NdgPattern\Form;

use Zend\Form\Form;

class PatternForm extends Form
{
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
                'label'    => 'Pattern Name',
                'required' => true,
            ),
        ));
        $this->add(array(
            'name' => 'content',
            'type' => 'TextArea',
            'options' => array(
                'label' => 'Content',
            ),
            'attributes' => array(
                'rows' => '20',
                'cols' => '80',
            ),
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
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Submit',
                'id' => 'submitbutton',
            ),
        ));
    }
}
