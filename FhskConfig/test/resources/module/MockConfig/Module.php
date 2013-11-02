<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace MockConfig;

/**
 * Mock Config module for unit tests
 */
class Module
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
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__          => __DIR__ . '/src/' . __NAMESPACE__,
                    'FhSiteKit\FhskConfig' => __DIR__ . '/../../../../src/FhSiteKit/FhskConfig',
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
        return array(
            'FhSiteKit\FhskCore',
        );
    }

    /**
     * Get module service config
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'FhskConfigRegistry' => 'MockConfig\Service\Config',
            ),
            'factories' => array(
                'FhskConfig' =>  function($sm) {
                    $config = $sm->get('FhskConfigRegistry');

                    return $config;
                },
            ),
        );
    }
}
