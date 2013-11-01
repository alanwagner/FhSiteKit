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

use FhSiteKit\FhskCore\FhskEntity\Model\Entity;
use Zend\InputFilter\InputFilter;

/**
 * The Instance entity
 */
class Instance extends Entity
{
    /**
     * Instance id
     * @var int
     */
    public $id = null;

    /**
     * Instance name
     * @var string
     */
    public $name = '';

    /**
     * Pattern name
     * @var string
     */
    public $pattern_name = '';

    /**
     * Instance description
     * @var string
     */
    public $description = '';

    /**
     * Instance status
     * @var string
     */
    public $status = '';

    /**
     * Instance is archived?
     * @var int
     */
    public $is_archived = 0;

    /**
     * Instance created at
     * @var string
     */
    public $created_at = null;

    /**
     * Instance updated at
     * @var string
     */
    public $updated_at = null;

    /**
     * Get array of names of entity's public properties
     * @return array
     */
    public static function getPropList()
    {
        return array(
            'id',
            'name',
            'pattern_name',
            'description',
            'status',
            'is_archived',
            'created_at',
            'updated_at',
        );
    }

    /**
     * Get the input filter
     * @return \Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
        }

        return $this->inputFilter;
    }
}
