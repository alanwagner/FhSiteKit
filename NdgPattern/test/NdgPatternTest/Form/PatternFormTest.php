<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgPatternTest\Form;

use NdgPattern\Model\PatternTable;
use NdgTemplate\Form\TemplateForm;
use Zend\Db\ResultSet\ResultSet;

use NdgPattern\Form\PatternForm;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the PatternForm class
 */
class PatternFormTest extends PHPUnit_Framework_TestCase
{
    public function testFormIsStructuredProperly()
    {
        $form = new PatternForm();
        $this->assertTrue($form->has('id'));
        $this->assertTrue($form->has('name'));
        $this->assertTrue($form->has('content'));
        $this->assertTrue($form->has('description'));
    }
}
