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

use FhSiteKit\FhskCore\Model\EntityTableInterface;

/**
 * Config table interface
 */
interface ConfigTableInterface extends EntityTableInterface
{
    /**
     * Fetch all config rows
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll();

    /**
     * Get a single config row by id
     * @param int $id
     * @throws \Exception
     * @return Config
     */
    public function getConfig($id);

    /**
     * Prepare and save config row
     *
     * Could be creating a new row or updating an existing one
     *
     * @param Config $config
     * @throws \Exception
     */
    public function saveConfig(Config $config);

    /**
     * Delete a config rowS
     * @param int $id
     */
    public function deleteConfig($id);
}
