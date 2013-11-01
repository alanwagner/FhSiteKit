<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgNetwork\NdgInstance\Model;

use FhSiteKit\FhskCore\FhskEntity\Model\EntityTable;

/**
 * Instance table gateway
 */
class InstanceTable extends EntityTable implements InstanceTableInterface
{
    /**
     * Fetch all instances
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Fetch only active or archived instances
     * @param int $isArchived  0 or 1
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchByIsArchived($isArchived)
    {
        $resultSet = $this->tableGateway->select(array('is_archived' => $isArchived));
        return $resultSet;
    }

    /**
     * Get a single instance by id
     * @param int $id
     * @throws \Exception
     * @return Instance
     */
    public function getInstance($id)
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
     * Prepare and save instance data
     *
     * Could be creating a new instance or updating an existing one
     *
     * @param Instance $instance
     * @return Instance
     * @throws \Exception
     */
    public function saveInstance(Instance $instance)
    {
        $data = array(
            'name'         => $instance->name,
            'pattern_name' => $instance->pattern_name,
            'description'  => $instance->description,
            'status'       => $instance->status,
            'is_archived'  => $instance->is_archived,
        );

        $id = (int) $instance->id;
        if ($id == 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getInstance($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception(sprintf('Instance %d does not exist', $id));
            }
        }

        return $this->getInstance($id);
    }

    /**
     * Delete an instance
     * @param int $id
     */
    public function deleteInstance($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
