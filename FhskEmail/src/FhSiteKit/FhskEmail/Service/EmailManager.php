<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskEmail\Service;

use FhSiteKit\FhskEmail\Model\EmailTableInterface;

/**
 * Centralized email manager service
 */
class EmailManager
{
    /**
     * The email table gateway
     * @var EmailTableInterface
     */
    protected $emailTable;

    /**
     * Internal queue
     * @var array
     */
    protected static $queue = array();

    /**
     * Add a key-data pair to the list
     * @param string|array $key
     */
    public static function registerEmail($key, $data)
    {
        static::$queue[$key] = $data;
    }

    /**
     * Get array of all currently registered data
     * @return array
     */
    public function getEmailRegistry()
    {
        return static::$queue;
    }

    /**
     * Get registered email data for a given key, or null if key not registered
     * @return array
     */
    public function getEmailRegistryByKey($key)
    {
        if (! empty(static::$queue[$key])) {

            return static::$queue[$key];
        } else {

            return null;
        }
    }

    /**
     * Get a email entity, by key
     *
     * Returns null if key not registered
     * @param string $key
     * @return \FhSiteKit\FhskEmail\Model\Email|null
     */
    public function getEmailByKey($key)
    {
        $email = $this->emailTable->fetchEmailByKey($key);

        if ($email === null && static::hasKey($key)) {
            //  key is registered but not in db
            //  Get the Email entity
            //    don't assume it's from this module, or hard-code the class
            $email = $this->emailTable
                           ->getTableGateway()
                           ->getResultSetPrototype()
                           ->getArrayObjectPrototype();

            $email->exchangeArray(array('email_key' => $key));
        }

        return $email;
    }

    /**
     * Is a given key registered?
     * @param string $key
     * @return boolean
     */
    public static function hasKey($key)
    {
        return isset(static::$queue[$key]);
    }

    /**
     * Set email table
     * @param EmailTableInterface $emailTable
     * @return \FhSiteKit\FhskEmail\Service\EmailManager
     */
    public function setEmailTable(EmailTableInterface $emailTable)
    {
        $this->emailTable = $emailTable;

        return $this;
    }
}
