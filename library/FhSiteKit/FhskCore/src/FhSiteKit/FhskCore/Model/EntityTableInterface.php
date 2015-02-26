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
 * Entity table interface
 */
interface EntityTableInterface
{
    /**
     * Get table gateway
     * @return \Zend\Db\TableGateway\TableGateway
     */
    public function getTableGateway();
}
