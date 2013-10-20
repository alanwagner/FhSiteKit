<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace SilPattern\Model;

use Zend\Stdlib\ArrayObject;

/**
 * The Pattern entity
 */
class Pattern
{
    public $id;
    public $name;
    public $content;
    public $description;

    /**
     * Exchange the current array with another array or object.
     *
     * @param  array|object $input
     * @return array        Returns the old array
     * @see ArrayObject::exchangeArray()
     */
    public function exchangeArray($input)
    {
        // handle arrayobject, iterators and the like:
        if (is_object($input) && ($input instanceof ArrayObject || $input instanceof \ArrayObject)) {
            $input = $input->getArrayCopy();
        }
        if (!is_array($input)) {
            $input = (array) $input;
        }

        $propList = array(
            'id',
            'name',
            'content',
            'description',
        );

        $old = array();

        foreach ($propList as $prop) {
            $old[$prop] = $this->$prop;
            $this->$prop = (!empty($input[$prop])) ? $input[$prop] : null;
        }

        return $old;
    }
}
