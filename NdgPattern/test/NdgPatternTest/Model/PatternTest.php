<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgPatternTest\Model;

use NdgPattern\Model\Pattern;
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
        $this->assertNull($pattern->name);
        $this->assertNull($pattern->content);
        $this->assertNull($pattern->description);
        $this->assertNull($pattern->is_archived);
        $this->assertNull($pattern->created_at);
        $this->assertNull($pattern->updated_at);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $pattern = $this->getPatternWithData();
        $data  = $this->getDataArray();

        $this->assertSame($data['id'], $pattern->id);
        $this->assertSame($data['name'], $pattern->name);
        $this->assertSame($data['content'], $pattern->content);
        $this->assertSame($data['description'], $pattern->description);
        $this->assertSame($data['is_archived'], $pattern->is_archived);
        $this->assertSame($data['created_at'], $pattern->created_at);
        $this->assertSame($data['updated_at'], $pattern->updated_at);
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $pattern = $this->getPatternWithData();
        $pattern->exchangeArray(array());

        $this->assertNull($pattern->id);
        $this->assertNull($pattern->name);
        $this->assertNull($pattern->content);
        $this->assertNull($pattern->description);
        $this->assertNull($pattern->is_archived);
        $this->assertNull($pattern->created_at);
        $this->assertNull($pattern->updated_at);
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
     * @return NdgPattern\Model\Pattern
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