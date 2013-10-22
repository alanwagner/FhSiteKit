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

use FhskEntity\Model\Entity;

/**
 * The Pattern entity
 */
class Pattern extends Entity
{
    public $id;
    public $name;
    public $content;
    public $description;

    /**
     * Get array of names of entity's public properties
     *
     * @return array
     */
    public static function getPropList()
    {
        return array(
            'id',
            'name',
            'content',
            'description',
        );
    }
}
