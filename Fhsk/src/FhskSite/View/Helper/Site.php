<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskSite\View\Helper;

use FhskSite\Core\Site as FhskSite;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Helper to provide Site properties to the view
 */
class Site extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * Invokable property to get the starting path for the Site's URLs.
     *
     * The first element of the returned path is the siteKey.
     *
     * @var string
     */
    const PROP_PATH = 'path';

    /**
     * Invokable property to get the Site name
     * @var string
     */
    const PROP_NAME = 'name';

    /**
     * The service locator interface
     * @var ServiceLocatorInterface
     */
    protected $services;

    /**
     * Provide Site properties to the view
     * @param string $prop  One of: 'path', 'name'
     * @return string
     */
    public function __invoke($prop)
    {
        $val = null;
        switch ($prop) {
            case self::PROP_PATH :
                $val = FhskSite::getKey();
                break;
            case self::PROP_NAME :
                $val = $this->getGlobalServiceLocator()->get('FhskSite')->getName();
        }

        return $val;
    }

    /**
     * Set service locator
     *
     * Is set with Zend\View\HelperPluginManager
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * Returns Zend\View\HelperPluginManager
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->services;
    }

    /**
     * Get application-wide service locator
     *
     * $services is set with Zend\View\HelperPluginManager
     * so we need to call its getServiceLocator() to get the application-wide one
     *
     * @return Zend\ServiceManager\ServiceManager
     */
    public function getGlobalServiceLocator()
    {
        return $this->getServiceLocator()->getServiceLocator();
    }
}
