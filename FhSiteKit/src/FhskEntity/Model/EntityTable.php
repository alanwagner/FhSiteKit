<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
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
     * Constructor
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    protected function useRowDataResult()
    {
        $tableGateway = $this->tableGateway;
        $table = $tableGateway->getTable();
        $adapter = $tableGateway->getAdapter();
        $features = $tableGateway->getFeatureSet();
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new RowData());
        $sql = $tableGateway->getSql();

        $this->tableGateway = new TableGateway($table, $adapter, $features, $resultSetPrototype, $sql);
    }
}
