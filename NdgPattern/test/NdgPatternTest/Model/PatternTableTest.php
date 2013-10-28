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

use Ndg\NdgPattern\Model\PatternTable;
use Ndg\NdgPattern\Model\Pattern;
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
            ->with(array('id' => 420))
            ->will($this->returnValue($resultSet));

        $patternTable = new PatternTable($mockTableGateway);

        $this->assertSame($pattern, $patternTable->getPattern(420));
    }

    public function testCanRetrieveActivePatterns()
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
            ->with(array('is_archived' => 0))
            ->will($this->returnValue($resultSet));

        $patternTable = new PatternTable($mockTableGateway);

        $this->assertSame($pattern, $patternTable->fetchByIsArchived(0)->current());
    }

    public function testCanRetrieveArchivedPatterns()
    {
        $pattern = $this->getPatternWithData();
        $pattern->is_archived = 1;
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
            ->with(array('is_archived' => 1))
            ->will($this->returnValue($resultSet));

        $patternTable = new PatternTable($mockTableGateway);

        $this->assertSame($pattern, $patternTable->fetchByIsArchived(1)->current());
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
            ->with(array('id' => 420));

        $patternTable = new PatternTable($mockTableGateway);
        $patternTable->deletePattern(420);
    }

    public function testSavePatternWillInsertNewPatternsIfTheyDontAlreadyHaveAnId()
    {
        $patternData = $this->getDataArray();
        $created = $patternData['created_at'];
        unset($patternData['id']);
        unset($patternData['created_at']);

        $pattern     = new Pattern();
        $pattern->exchangeArray($patternData);
        $patternData['created_at'] = $created;

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('insert', 'select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($patternData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Pattern());
        $resultSet->initialize(array($this->getPatternWithData()));
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->will($this->returnValue($resultSet));;

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
        $mockTableGateway->expects($this->any())
            ->method('select')
            ->with(array('id' => 420))
            ->will($this->returnValue($resultSet));

        $patternData = $this->getDataArray();
        unset($patternData['id']);
        unset($patternData['created_at']);

        $mockTableGateway->expects($this->once())
            ->method('update')
            ->with($patternData, array('id' => 420));

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
            ->with(array('id' => 420))
            ->will($this->returnValue($resultSet));

        $patternTable = new PatternTable($mockTableGateway);

        try {
            $patternTable->getPattern(420);
        }
        catch (\Exception $e) {
            $this->assertSame('Could not find row 420', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }

    public function testGetTableGateway()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );

        $patternTable = new PatternTable($mockTableGateway);

        $this->assertSame($mockTableGateway, $patternTable->getTableGateway());
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
        );
    }
}
