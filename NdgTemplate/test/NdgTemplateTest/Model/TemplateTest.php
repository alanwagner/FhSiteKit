<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgTemplateTest\Model;

use NdgTemplate\Model\Template;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the Template entity
 */
class TemplateTest extends PHPUnit_Framework_TestCase
{
    public function testTemplateInitialState()
    {
        $template = new Template();

        $this->assertNull($template->id);
        $this->assertNull($template->name);
        $this->assertNull($template->content);
        $this->assertNull($template->description);
        $this->assertNull($template->is_archived);
        $this->assertNull($template->created_at);
        $this->assertNull($template->updated_at);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $template = $this->getTemplateWithData();
        $data  = $this->getDataArray();

        $this->assertSame($data['id'], $template->id);
        $this->assertSame($data['name'], $template->name);
        $this->assertSame($data['content'], $template->content);
        $this->assertSame($data['description'], $template->description);
        $this->assertSame($data['is_archived'], $template->is_archived);
        $this->assertSame($data['created_at'], $template->created_at);
        $this->assertSame($data['updated_at'], $template->updated_at);
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $template = $this->getTemplateWithData();
        $template->exchangeArray(array());

        $this->assertNull($template->id);
        $this->assertNull($template->name);
        $this->assertNull($template->content);
        $this->assertNull($template->description);
        $this->assertNull($template->is_archived);
        $this->assertNull($template->created_at);
        $this->assertNull($template->updated_at);
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $template = $this->getTemplateWithData();
        $data  = $this->getDataArray();
        $copyArray = $template->getArrayCopy();

        $this->assertSame($data['id'], $copyArray['id']);
        $this->assertSame($data['name'], $copyArray['name']);
        $this->assertSame($data['content'], $copyArray['content']);
        $this->assertSame($data['description'], $copyArray['description']);
        $this->assertSame($data['is_archived'], $copyArray['is_archived']);
        $this->assertSame($data['created_at'], $copyArray['created_at']);
        $this->assertSame($data['updated_at'], $copyArray['updated_at']);
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $template = new Template();

        $inputFilter = $template->getInputFilter();

        $this->assertSame(4, $inputFilter->count());
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('name'));
        $this->assertTrue($inputFilter->has('content'));
        $this->assertTrue($inputFilter->has('description'));
    }

    /**
     * Get Template entity initialized with standard data
     * @return NdgTemplate\Model\Template
     */
    protected function getTemplateWithData()
    {
        $template = new Template();
        $data  = $this->getDataArray();
        $template->exchangeArray($data);

        return $template;
    }

    /**
     * Get standard data as array
     * @return array
     */
    protected function getDataArray()
    {
        return array(
            'id'          => 420,
            'name'        => 'template name',
            'content'     => "1 2 3\n2 1 3\n3 1 2",
            'description' => 'N=3, Z=2',
            'is_archived' => 0,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
    }
}