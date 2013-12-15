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
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

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
     * The email transport
     * @var TransportInterface
     */
    protected $emailTransport;

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

    public function sendEmail($key, $toAddress, $toName, $data)
    {
        $email = $this->getEmailByKey($key);
        if (! empty($email) && ! empty($email->id)) {
            $message = new Message();
            $message->addTo($toAddress, $toName);
            $message->setFrom($email->from_address, $email->from_name);
            $subject = $this->incorporateVariables($email->subject_template, $data);
            $message->setSubject($subject);
            $body = $this->incorporateVariables($email->body_template, $data);
            $message->setBody($body);

            $this->emailTransport->send($message);

            return true;
        }

        return false;
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

    protected function incorporateVariables($str, $data)
    {
        foreach ($data as $key => $val) {
            $str = str_replace('#' . $key, $val, $str);
        }

        return $str;
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

    /**
     * Set email transport
     * @param TransportInterface $emailTransport
     * @return \FhSiteKit\FhskEmail\Service\EmailManager
     */
    public function setEmailTransport(TransportInterface $emailTransport)
    {
        $this->emailTransport = $emailTransport;

        return $this;
    }
}
