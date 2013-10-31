<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskConfigTest\Form;

use FhSiteKit\FhskConfig\Form\ConfigForm;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the ConfigForm class
 */
class ConfigFormTest extends PHPUnit_Framework_TestCase
{
    public function testFormIsStructuredProperly()
    {
        $form = new ConfigForm();
        $this->assertTrue($form->has('id'));
        $this->assertTrue($form->has('config_key'));
        $this->assertTrue($form->has('config_value'));
    }
}
