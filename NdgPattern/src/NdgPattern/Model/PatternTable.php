<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgPattern\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Pattern table gateway
 */
class PatternTable implements PatternTableInterface
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
     * Fetch all patterns
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Fetch only active or archived patterns
     * @param int $isArchived  0 or 1
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchByIsArchived($isArchived)
    {
        $resultSet = $this->tableGateway->select(array('is_archived' => $isArchived));
        return $resultSet;
    }

    /**
     * Get a single pattern by id
     * @param int $id
     * @throws \Exception
     * @return Pattern
     */
    public function getPattern($id)
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
     * Prepare and save pattern data
     *
     * Could be creating a new pattern or updating an existing one
     *
     * @param Pattern $pattern
     * @throws \Exception
     */
    public function savePattern(Pattern $pattern)
    {
        $data = array(
            'name'        => $pattern->name,
            'content'     => $pattern->content,
            'description' => $pattern->description,
            'is_archived' => empty($pattern->is_archived) ? 0 : 1,
        );

        $id = (int) $pattern->id;
        if ($id == 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPattern($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception(sprintf('Pattern %d does not exist', $id));
            }
        }
    }

    /**
     * Delete a pattern
     * @param int $id
     */
    public function deletePattern($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
