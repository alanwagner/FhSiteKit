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
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $pattern = $this->getPatternWithData();
        $data  = $this->getDataArray();

        $this->assertSame($data['id'], $pattern->id);
        $this->assertSame($data['name'], $pattern->name);
        $this->assertSame($data['content'], $pattern->content);
        $this->assertSame($data['description'], $pattern->description);
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $pattern = $this->getPatternWithData();
        $pattern->exchangeArray(array());

        $this->assertNull($pattern->id);
        $this->assertNull($pattern->name);
        $this->assertNull($pattern->content);
        $this->assertNull($pattern->description);
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
            'id'          => 123,
            'name'        => 'pattern name',
            'content'     => "1 2 3\n2 1 3\n3 1 2",
            'description' => 'N=3, Z=2',
        );
    }
}