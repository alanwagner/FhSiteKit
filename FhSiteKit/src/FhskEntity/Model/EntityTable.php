<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskEntity\Model;

use Zend\Db\ResultSet\ResultSet;
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
     * The table gateway to use for queries that take a RowData result object prototype
     * @var TableGateway
     */
    protected $rowDataTableGateway;

    /**
     * Constructor
     * @param TableGateway $tableGateway
     * @param TableGateway $rowDataTableGateway
     */
    public function __construct(TableGateway $tableGateway, TableGateway $rowDataTableGateway = null)
    {
        $this->tableGateway = $tableGateway;
        if ($rowDataTableGateway !== null) {
            $this->rowDataTableGateway = $rowDataTableGateway;
        }
    }

    protected function useRowDataResult()
    {
        $this->tableGateway = $this->rowDataTableGateway;
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
