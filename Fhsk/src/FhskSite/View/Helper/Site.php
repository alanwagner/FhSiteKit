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

class Site extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * Property names
     * @var string
     */
    const PROP_PATH = 'path';
    const PROP_NAME = 'name';

    /**
     * The service locator interface
     * @var ServiceLocatorInterface
     */
    protected $services;

    /**
     * Provide site properties to the view
     *
     * @param string $prop
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
                $val = $this->getServiceLocator()->get('FhskSite')->getName();
        }

        return $val;
    }

    /**
     * Set service locator
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
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        //  $services is set with Zend\View\HelperPluginManager
        //  so we need to call _its_ getServiceLocator()
        //  to get the one we want

        return $this->services->getServiceLocator();
    }
}