<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgTemplateTest\Model;

use FhskEntity\Model\RowData;
use NdgPattern\Model\PatternTable;
use NdgTemplate\Model\TemplateTable;
use NdgTemplate\Model\Template;
use NdgTemplateTest\Bootstrap;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\Pdo\Pdo;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the TemplateTable class
 */
class TemplateTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllTemplates()
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

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());

        $this->assertSame($resultSet, $templateTable->fetchAll());
    }

    public function testCanRetrieveATemplateByItsId()
    {
        $template = $this->getTemplateWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Template());
        $resultSet->initialize(array($template));

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

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());

        $this->assertSame($template, $templateTable->getTemplate(420));
    }

    public function testCanRetrieveActiveTemplates()
    {
        $template = $this->getTemplateWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Template());
        $resultSet->initialize(array($template));

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

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());

        $this->assertSame($template, $templateTable->fetchByIsArchived(0)->current());
    }

    public function testCanRetrieveArchivedTemplates()
    {
        $template = $this->getTemplateWithData();
        $template->is_archived = 1;
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Template());
        $resultSet->initialize(array($template));

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

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());

        $this->assertSame($template, $templateTable->fetchByIsArchived(1)->current());
    }

    public function testCanRetrieveActiveDataRows()
    {
        $rowData = $this->getRowWithData();
        $rowData->is_archived = 0;
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new RowData());
        $resultSet->initialize(array($rowData));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array(),
            array('template'),
            '',
            false
        );
        $mockTableGateway->expects($this->never())
            ->method('selectWith');

        $mockRowDataTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array(),
            array('template'),
            '',
            false
        );
        $mockRowDataTableGateway->expects($this->once())
            ->method('selectWith')
            ->will($this->returnValue($resultSet));

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable(), $mockRowDataTableGateway);

        $this->assertSame($rowData, $templateTable->fetchDataWithPatternByIsArchived(0)->current());
    }

    public function testCanRetrieveArchivedDataRows()
    {
        $rowData = $this->getRowWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new RowData());
        $resultSet->initialize(array($rowData));

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array(),
            array('template'),
            '',
            false
        );
        $mockTableGateway->expects($this->never())
            ->method('selectWith');

        $mockRowDataTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array(),
            array('template'),
            '',
            false
        );
        $mockRowDataTableGateway->expects($this->once())
            ->method('selectWith')
            ->will($this->returnValue($resultSet));

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable(), $mockRowDataTableGateway);

        $this->assertSame($rowData, $templateTable->fetchDataWithPatternByIsArchived(1)->current());
    }

    public function testCanDeleteATemplateByItsId()
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

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());
        $templateTable->deleteTemplate(420);
    }

    public function testSaveTemplateWillInsertNewTemplatesIfTheyDontAlreadyHaveAnId()
    {
        $templateData = $this->getTemplateDataArray();
        $created = $templateData['created_at'];
        unset($templateData['id']);
        unset($templateData['created_at']);
        unset($templateData['updated_at']);

        $template     = new Template();
        $template->exchangeArray($templateData);
        $templateData['created_at'] = $created;

        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('insert', 'select'),
            array(),
            '',
            false
        );
        $mockTableGateway->expects($this->once())
            ->method('insert')
            ->with($templateData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Template());
        $resultSet->initialize(array($this->getTemplateWithData()));
        $mockTableGateway->expects($this->once())
            ->method('select')
            ->will($this->returnValue($resultSet));

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());
        $templateTable->saveTemplate($template);
    }

    public function testSaveTemplateWillUpdateExistingTemplatesIfTheyAlreadyHaveAnId()
    {
        $template = $this->getTemplateWithData();
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Template());
        $resultSet->initialize(array($template));

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

        $templateData = $this->getTemplateDataArray();
        unset($templateData['id']);
        unset($templateData['created_at']);
        unset($templateData['updated_at']);

        $mockTableGateway->expects($this->once())
            ->method('update')
            ->with($templateData, array('id' => 420));

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());
        $templateTable->saveTemplate($template);
    }

    public function testExceptionIsThrownWhenGettingNonExistentTemplate()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Template());
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

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());

        try {
            $templateTable->getTemplate(420);
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

        $templateTable = new TemplateTable($mockTableGateway, $this->getPatternTable());

        $this->assertSame($mockTableGateway, $templateTable->getTableGateway());
    }

    protected function getPatternTable()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('pattern'),
            array(),
            '',
            false
        );

        return new PatternTable($mockTableGateway);
    }

    /**
     * Get Template entity initialized with standard data
     * @return NdgTemplate\Model\Template
     */
    protected function getTemplateWithData()
    {
        $template = new Template();
        $data  = $this->getTemplateDataArray();
        $template->exchangeArray($data);

        return $template;
    }

    /**
     * Get standard template data as array
     * @return array
     */
    protected function getTemplateDataArray()
    {
        return array(
            'id'            => 420,
            'pattern_id'    => 429,
            'name'          => 'template name',
            'description'   => 'N=3, Z=2',
            'instance_name' => '4.## Cond 1 #pattern',
            'serial'        => 19,
            'is_archived'   => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        );
    }

    /**
     * Get a RowData object initialized with data
     */
    protected function getRowWithData()
    {
        $rowData = new RowData();
        $data = $this->getTemplateDataArray();
        $data['pattern__name'] = 'pattern_name';
        $data['pattern__content'] = "1 2 3\n2 1 3\n3 1 2";

        $rowData->exchangeArray($data);

        return $rowData;
    }
}
