<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskEmail\Model;

use FhSiteKit\FhskCore\Model\EntityTableInterface;

/**
 * Email table interface
 */
interface EmailTableInterface extends EntityTableInterface
{
    /**
     * Fetch all email rows
     * @return \Laminas\Db\ResultSet\ResultSet
     */
    public function fetchAll();

    /**
     * Get a single email row by id
     * @param int $id
     * @throws \Exception
     * @return Email
     */
    public function getEmail($id);

    /**
     * Fetch a single email row by key
     * @param string $key
     * @return Email|null
     */
    public function fetchEmailByKey($key);

    /**
     * Prepare and save email row
     *
     * Could be creating a new row or updating an existing one
     *
     * @param Email $email
     * @throws \Exception
     */
    public function saveEmail(Email $email);

    /**
     * Delete a email row
     * @param int $id
     */
    public function deleteEmail($id);
}
