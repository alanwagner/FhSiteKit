<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgPattern\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Pattern table interface
 */
interface PatternTableInterface
{
    /**
     * Fetch all patterns
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll();

    /**
     * Fetch only active or archived patterns
     * @param int $isArchived  0 or 1
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchByIsArchived($isArchived);

    /**
     * Get a single pattern by id
     * @param int $id
     * @throws \Exception
     * @return Pattern
     */
    public function getPattern($id);

    /**
     * Prepare and save pattern data
     *
     * Could be creating a new pattern or updating an existing one
     *
     * @param Pattern $pattern
     * @throws \Exception
     */
    public function savePattern(Pattern $pattern);

    /**
     * Delete a pattern
     * @param int $id
     */
    public function deletePattern($id);
}
