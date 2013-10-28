<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgTemplateTest\Form;

use Ndg\NdgPattern\Model\PatternTable;
use Ndg\NdgPattern\Model\Pattern;
use Ndg\NdgTemplate\Form\TemplateForm;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the TemplateForm class
 */
class TemplateFormTest extends PHPUnit_Framework_TestCase
{
    public function testFormIsStructuredProperly()
    {
        $form = new TemplateForm($this->GetPatternTable());
        $this->assertTrue($form->has('id'));
        $this->assertTrue($form->has('name'));
        $this->assertTrue($form->has('pattern_id'));
        $this->assertTrue($form->has('instance_name'));
        $this->assertTrue($form->has('description'));

        $patternSelect = $form->get('pattern_id');
        $valueOptions = $patternSelect->getOption('value_options');
        $expectedValueOptions = array(420 => 'pattern name', 421 => 'pattern name');
        $this->assertSame($expectedValueOptions, $valueOptions);
    }

    /**
     * Get a mock PatternTable
     * @return mixed
     */
    protected function getPatternTable()
    {
        $patternTableMock = $this->getMockBuilder('Ndg\NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $data = array();
        $data[] = $this->getPatternWithData();
        $pattern = $this->getPatternWithData();
        $pattern->id = 421;
        $data[] = $pattern;

        $resultSet = new ResultSet();
        $resultSet->initialize($data);

        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue($resultSet));

        return $patternTableMock;
    }

    /**
     * Get Pattern entity initialized with standard data
     * @return Ndg\NdgPattern\Model\Pattern
     */
    protected function getPatternWithData()
    {
        $pattern = new Pattern();
        $data  = $this->getPatternDataArray();
        $pattern->exchangeArray($data);

        return $pattern;
    }

    /**
     * Get standard pattern data as array
     * @return array
     */
    protected function getPatternDataArray()
    {
        return array(
            'id'          => 420,
            'name'        => 'pattern name',
            'content'     => "1 2 3\n2 1 3\n3 1 2",
            'description' => 'N=3, Z=2',
            'is_archived' => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
    }
}
