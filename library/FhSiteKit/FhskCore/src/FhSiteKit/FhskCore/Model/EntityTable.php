<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Common model entity table functions
 */
class EntityTable
{
    /**
     * The table gateway
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * Constructor
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Get table gateway
     * @return TableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }
}
