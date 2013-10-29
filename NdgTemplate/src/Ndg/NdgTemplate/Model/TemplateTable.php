<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgTemplate\Model;

use FhSiteKit\FhskCore\FhskEntity\Model\EntityTable;
use Ndg\NdgPattern\Model\PatternTableInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * Template table gateway
 */
class TemplateTable extends EntityTable implements TemplateTableInterface
{
    /**
     * Pattern Table
     * @var PatternTableInterface
     */
    protected $patternTable;

    /**
     * Constructor
     * @param TableGateway $tableGateway
     * @param PatternTableInterface $patternTable
     * @param TableGateway $rowDataTableGateway
     */
    public function __construct(TableGateway $tableGateway, PatternTableInterface $patternTable, TableGateway $rowDataTableGateway = null)
    {
        $this->patternTable = $patternTable;
        parent::__construct($tableGateway, $rowDataTableGateway);
    }

    /**
     * Fetch all templates
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Fetch only active or archived templates
     * @param int $isArchived  0 or 1
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchByIsArchived($isArchived)
    {
        $resultSet = $this->tableGateway->select(array('is_archived' => $isArchived));
        return $resultSet;
    }

    /**
     * Fetch RowData only on active or archived patterns, with pattern data
     * @param int $isArchived  0 or 1
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchDataWithPatternByIsArchived($isArchived)
    {
        $this->useRowDataResult();

        $tableName = $this->tableGateway->getTable();
        $select = new Select($tableName);
        $select->where(array($tableName.'.is_archived' => $isArchived));
        $patternTableName = $this->patternTable->getTableGateway()->getTable();
        $select->join(
            array('p' => $patternTableName),
            'p.id = ' . $tableName . '.pattern_id',
            array(
                'pattern__name' => 'name',
                'pattern__content' => 'content'
            )
        );

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    /**
     * Get a single template by id
     * @param int $id
     * @throws \Exception
     * @return Template
     */
    public function getTemplate($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception(sprintf('Could not find row %d', $id));
        }
        return $row;
    }

    /**
     * Prepare and save template data
     *
     * Could be creating a new template or updating an existing one
     *
     * @param Template $template
     * @return Template
     * @throws \Exception
     */
    public function saveTemplate(Template $template)
    {
        $data = array(
            'name'          => $template->name,
            'pattern_id'    => $template->pattern_id,
            'description'   => $template->description,
            'instance_name' => $template->instance_name,
            'serial'        => $template->serial,
            'is_archived'   => $template->is_archived,
        );

        $id = (int) $template->id;
        if ($id == 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getTemplate($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception(sprintf('Template %d does not exist', $id));
            }
        }

        return $this->getTemplate($id);
    }

    /**
     * Delete a template
     * @param int $id
     */
    public function deleteTemplate($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
