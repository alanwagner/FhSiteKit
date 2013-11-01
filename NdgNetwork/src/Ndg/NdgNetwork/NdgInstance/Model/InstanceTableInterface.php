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

use FhSiteKit\FhskCore\FhskEntity\Model\EntityTableInterface;

/**
 * Instance table interface
 */
interface InstanceTableInterface extends EntityTableInterface
{
    /**
     * Fetch all instances
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll();

    /**
     * Fetch only active or archived instances
     * @param int $isArchived  0 or 1
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchByIsArchived($isArchived);

    /**
     * Get a single instance by id
     * @param int $id
     * @throws \Exception
     * @return Instance
     */
    public function getInstance($id);

    /**
     * Prepare and save instance data
     *
     * Could be creating a new instance or updating an existing one
     *
     * @param Instance $instance
     * @throws \Exception
     */
    public function saveInstance(Instance $instance);

    /**
     * Delete a instance
     * @param int $id
     */
    public function deleteInstance($id);
}
