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

use NdgPattern\Model\PatternTable;
use NdgPattern\Model\Pattern;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the PatternTable class
 */
class PatternTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllPatterns()
    {
        $resultSet = new ResultSet();
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with()
            ->will($this->returnValue($resultSet));

        $patternTable = new PatternTable($mockTableGateway);

        $this->assertSame($resultSet, $patternTable->fetchAll());
    }

    public function testCanRetrieveAPatternByItsId()
    {
        $pattern = $this->getPatternWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Pattern());
        $resultSet->initialize(array($pattern));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 123))
            ->will($this->returnValue($resultSet));

        $patternTable = new PatternTable($mockTableGateway);

        $this->assertSame($pattern, $patternTable->getPattern(123));
    }

    public function testCanDeleteAPatternByItsId()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('delete'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('delete')
            ->with(array('id' => 123));

        $patternTable = new PatternTable($mockTableGateway);
        $patternTable->deletePattern(123);
    }

    public function testSavePatternWillInsertNewPatternsIfTheyDontAlreadyHaveAnId()
    {
        $patternData = $this->getDataArray();
        unset($patternData['id']);

        $pattern     = new Pattern();
        $pattern->exchangeArray($patternData);

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('insert'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($patternData);

        $patternTable = new PatternTable($mockTableGateway);
        $patternTable->savePattern($pattern);
    }

    public function testSavePatternWillUpdateExistingPatternsIfTheyAlreadyHaveAnId()
    {
        $pattern = $this->getPatternWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Pattern());
        $resultSet->initialize(array($pattern));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select', 'update'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 123))
            ->will($this->returnValue($resultSet));

        $patternData = $this->getDataArray();
        unset($patternData['id']);

        $mockTableGateway->expects($this->once())
            ->method('update')
            ->with($patternData, array('id' => 123));

        $patternTable = new PatternTable($mockTableGateway);
        $patternTable->savePattern($pattern);
    }

    public function testExceptionIsThrownWhenGettingNonExistentPattern()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Pattern());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->with(array('id' => 123))
            ->will($this->returnValue($resultSet));

        $patternTable = new PatternTable($mockTableGateway);

        try {
            $patternTable->getPattern(123);
        }
        catch (\Exception $e) {
            $this->assertSame('Could not find row 123', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
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