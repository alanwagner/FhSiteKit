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

class PatternTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchByIsArchived($isArchived)
    {
        $resultSet = $this->tableGateway->select(array('is_archived' => $isArchived));
        return $resultSet;
    }

    public function getPattern($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePattern(Pattern $pattern)
    {
        $data = array(
            'name'        => $pattern->name,
            'content'     => $pattern->content,
            'description' => $pattern->description,
            'is_archived' => $pattern->is_archived,
        );

        $id = (int) $pattern->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPattern($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Pattern id does not exist');
            }
        }
    }

    public function deletePattern($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
