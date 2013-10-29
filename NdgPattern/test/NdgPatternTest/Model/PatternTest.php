<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgPatternTest\Model;

use Ndg\NdgPattern\Model\Pattern;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the Pattern entity
 */
class PatternTest extends PHPUnit_Framework_TestCase
{
    public function testPatternInitialState()
    {
        $pattern = new Pattern();

        $this->assertNull($pattern->id);
        $this->assertEquals('', $pattern->name);
        $this->assertEquals('', $pattern->content);
        $this->assertEquals('', $pattern->description);
        $this->assertEquals(0, $pattern->is_archived);
        $this->assertEquals('', $pattern->created_at);
        $this->assertEquals('', $pattern->updated_at);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $pattern = new Pattern();
        $data  = $this->getDataArray();
        $pattern->exchangeArray($data);

        $this->assertSame($data['id'], $pattern->id);
        $this->assertSame($data['name'], $pattern->name);
        $this->assertSame($data['content'], $pattern->content);
        $this->assertSame($data['description'], $pattern->description);
        $this->assertSame($data['is_archived'], $pattern->is_archived);
        $this->assertSame($data['created_at'], $pattern->created_at);
        $this->assertSame($data['updated_at'], $pattern->updated_at);
    }

    public function testPopulateSetsPropertiesCorrectly()
    {
        $pattern = new Pattern();
        $data  = $this->getDataArray();
        $pattern->populate($data);

        $this->assertSame($data['id'], $pattern->id);
        $this->assertSame($data['name'], $pattern->name);
        $this->assertSame($data['content'], $pattern->content);
        $this->assertSame($data['description'], $pattern->description);
        $this->assertSame($data['is_archived'], $pattern->is_archived);
        $this->assertSame($data['created_at'], $pattern->created_at);
        $this->assertSame($data['updated_at'], $pattern->updated_at);
    }

    public function testExchangeArrayLeavesPropertiesAloneIfKeysAreNotPresent()
    {
        $pattern = $this->getPatternWithData();
        $copy = $pattern->getArrayCopy();
        $pattern->exchangeArray(array());

        $this->assertSame($copy['id'], $pattern->id);
        $this->assertSame($copy['name'], $pattern->name);
        $this->assertSame($copy['content'], $pattern->content);
        $this->assertSame($copy['description'], $pattern->description);
        $this->assertSame($copy['is_archived'], $pattern->is_archived);
        $this->assertSame($copy['created_at'], $pattern->created_at);
        $this->assertSame($copy['updated_at'], $pattern->updated_at);
    }

    public function testPopulateSetsPropertiesToDefaultIfKeysAreNotPresent()
    {
        $pattern = $this->getPatternWithData();
        $pattern->populate(array());

        $this->assertNull($pattern->id);
        $this->assertEquals('', $pattern->name);
        $this->assertEquals('', $pattern->content);
        $this->assertEquals('', $pattern->description);
        $this->assertEquals(0, $pattern->is_archived);
        $this->assertEquals('', $pattern->created_at);
        $this->assertEquals('', $pattern->updated_at);
    }

    public function testExchangeArrayReturnsExistingValues()
    {
        $pattern = $this->getPatternWithData();
        $copyArray = $pattern->getArrayCopy();
        $old = $pattern->exchangeArray(array());

        $this->assertSame($copyArray['id'], $old['id']);
        $this->assertSame($copyArray['name'], $old['name']);
        $this->assertSame($copyArray['content'], $old['content']);
        $this->assertSame($copyArray['description'], $old['description']);
        $this->assertSame($copyArray['is_archived'], $old['is_archived']);
        $this->assertSame($copyArray['created_at'], $old['created_at']);
        $this->assertSame($copyArray['updated_at'], $old['updated_at']);
    }

    public function testPopulateReturnsExistingValues()
    {
        $pattern = $this->getPatternWithData();
        $copyArray = $pattern->getArrayCopy();
        $old = $pattern->populate(array());

        $this->assertSame($copyArray['id'], $old['id']);
        $this->assertSame($copyArray['name'], $old['name']);
        $this->assertSame($copyArray['content'], $old['content']);
        $this->assertSame($copyArray['description'], $old['description']);
        $this->assertSame($copyArray['is_archived'], $old['is_archived']);
        $this->assertSame($copyArray['created_at'], $old['created_at']);
        $this->assertSame($copyArray['updated_at'], $old['updated_at']);
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $pattern = $this->getPatternWithData();
        $data  = $this->getDataArray();
        $copyArray = $pattern->getArrayCopy();

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
        $pattern = new Pattern();

        $inputFilter = $pattern->getInputFilter();

        $this->assertSame(4, $inputFilter->count());
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('name'));
        $this->assertTrue($inputFilter->has('content'));
        $this->assertTrue($inputFilter->has('description'));
    }

    /**
     * Get Pattern entity initialized with standard data
     * @return Ndg\NdgPattern\Model\Pattern
     */
    protected function getPatternWithData()
    {
        $pattern = new Pattern();
        $data  = $this->getDataArray();
        $pattern->exchangeArray($data);

        return $pattern;
    }

    /**
     * Get standard data as array
     * @return array
     */
    protected function getDataArray()
    {
        return array(
            'id'          => 420,
            'name'        => 'pattern name',
            'content'     => "1 2 3\n2 1 3\n3 1 2",
            'description' => 'N=3, Z=2',
            'is_archived' => 0,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
    }
}
