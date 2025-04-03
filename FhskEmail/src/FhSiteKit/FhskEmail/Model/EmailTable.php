<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskEmail\Model;

use FhSiteKit\FhskCore\Model\EntityTable;
use Laminas\Db\Sql\Where;

/**
 * Email table gateway
 */
class EmailTable extends EntityTable implements EmailTableInterface
{
    /**
     * Fetch all email rows
     * @return \Laminas\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();

        return $resultSet;
    }

    /**
     * Get a single email row by id
     * @param int $id
     * @throws \Exception
     * @return Email
     */
    public function getEmail($id)
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
     * Fetch a single email row by key
     * @param string $key
     * @return Email|null
     */
    public function fetchEmailByKey($key)
    {
        $rowset = $this->tableGateway->select(array('key' => $key));
        $row = $rowset->current();
        if (!$row) {

            return null;
        }

        return $row;
    }

    /**
     * Prepare and save email row
     *
     * Could be creating a new row or updating an existing one
     *
     * @param Email $email
     * @return Email
     * @throws \Exception
     */
    public function saveEmail(Email $email)
    {
        $data = $email->getDataToSave();

        $id = (int) $email->id;
        if ($id == 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getEmail($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception(sprintf('Email %d does not exist', $id));
            }
        }

        return $this->getEmail($id);
    }

    /**
     * Delete an email row
     * @param int $id
     */
    public function deleteEmail($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
