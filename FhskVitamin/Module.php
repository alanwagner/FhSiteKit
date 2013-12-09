<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskVitamin;

use FhSiteKit\FhskCore\Module\AbstractModule;

/**
 * Fhsk Core module setup class
 */
class Module extends AbstractModule
{
    /**
     * Get module config
     * @return array
     */
    public function getConfig()
    {
        return array();
    }

    /**
     * Get module autoloader config
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'FhSiteKit\FhskVitamin' => __DIR__ . '/src/FhSiteKit/FhskVitamin',
                ),
            ),
        );
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return array();
    }

    /**
     * Get module service config
     * @return array
     */
    public function getServiceConfig()
    {
        return array();
    }
}
