<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskConfig\Model;

use FhSiteKit\FhskCore\Model\EntityTable;
use Laminas\Db\Sql\Where;

/**
 * Config table gateway
 */
class ConfigTable extends EntityTable implements ConfigTableInterface
{
    /**
     * Fetch all config rows
     * @return \Laminas\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();

        return $resultSet;
    }

    /**
     * Get a single config row by id
     * @param int $id
     * @throws \Exception
     * @return Config
     */
    public function getConfig($id)
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
     * Get a single config row by key
     * @param string $key
     * @return Config|null
     */
    public function getConfigByKey($key)
    {
        $rowset = $this->tableGateway->select(array('config_key' => $key));
        $row = $rowset->current();
        if (!$row) {

            return null;
        }

        return $row;
    }

    /**
     * Prepare and save config row
     *
     * Could be creating a new row or updating an existing one
     *
     * @param Config $config
     * @return Config
     * @throws \Exception
     */
    public function saveConfig(Config $config)
    {
        $data = $config->getDataToSave();

        $id = (int) $config->id;
        if ($id == 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getConfig($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception(sprintf('Config %d does not exist', $id));
            }
        }

        return $this->getConfig($id);
    }

    /**
     * Delete a config row
     * @param int $id
     */
    public function deleteConfig($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
