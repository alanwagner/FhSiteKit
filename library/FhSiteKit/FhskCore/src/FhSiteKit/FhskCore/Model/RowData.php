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

/**
 * Generic row data class for db results across multiple tables
 */
class RowData
{
    /**
     * Internal data array
     * @var array
     */
    protected $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = array();
    }

    /**
     * Set data from array and return old data
     *
     * @param array $data
     * @return array
     */
    public function exchangeArray($data)
    {
        $oldData = $this->data;
        $this->data = $data;

        return $oldData;
    }

    /**
     * Offset exists
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Offset get
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Offset set
     *
     * @param  string $offset
     * @param  mixed $value
     * @return RowGateway
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
        return $this;
    }

    /**
     * Offset unset
     *
     * @param  string $offset
     * @return AbstractRowGateway
     */
    public function offsetUnset($offset)
    {
        $this->data[$offset] = null;
        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * To array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * __get
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            throw new \InvalidArgumentException('Not a valid column in this row: ' . $name);
        }
    }

    /**
     * __set
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * __isset
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * __unset
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        $this->offsetUnset($name);
    }
}
