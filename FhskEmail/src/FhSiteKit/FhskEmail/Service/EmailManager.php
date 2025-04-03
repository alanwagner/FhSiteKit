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
use Laminas\Mail\Message;
use Laminas\Mail\Transport\TransportInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

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
     * The service locator
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

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

            $this->getEmailTransport()->send($message);

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
        $email = $this->getEmailTable()->fetchEmailByKey($key);

        if ($email === null && static::hasKey($key)) {
            //  key is registered but not in db
            //  Get the Email entity
            //    don't assume it's from this module, or hard-code the class
            $email = $this->getEmailTable()
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
     * Get the Email Table
     * @return EmailTableInterface
     * @throws \Exception
     */
    protected function getEmailTable()
    {
        if (! $this->emailTable) {
            $emailTable = $this->getServiceLocator()
                ->get('Email\Model\EmailTable');
            if (! $emailTable instanceof EmailTableInterface) {
                throw new \Exception(sprintf('Email table must be an instance of FhSiteKit\FhskEmail\Model\EmailTableInterface, "%s" given.', get_class($emailTable)));
            }
            $this->emailTable = $emailTable;
        }

        return $this->emailTable;
    }

    /**
     * Get the Email Transport
     * @return TransportInterface
     * @throws \Exception
     */
    protected function getEmailTransport()
    {
        if (! $this->emailTransport) {
            $emailTransport = $this->getServiceLocator()
                ->get('FhSiteKit\EmailTransport');
            if (! $emailTransport instanceof TransportInterface) {
                throw new \Exception(sprintf('Email table must be an instance of Laminas\Mail\Transport\TransportInterface, "%s" given.', get_class($emailTransport)));
            }
            $this->emailTransport = $emailTransport;
        }

        return $this->emailTransport;
    }

    /**
     * Set serviceLocator
     * @param ServiceLocatorInterface $serviceLocator
     * @return \FhSiteKit\FhskEmail\Service\EmailManager
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get serviceLocator
     * @return ServiceLocatorInterface $serviceLocator
     */
    public function getServiceLocator()
    {

        return $this->serviceLocator;
    }
}
