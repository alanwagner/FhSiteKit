<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskConfig;

use FhSiteKit\FhskCore\AbstractModule;
use FhSiteKit\FhskCore\FhskSite\Controller\BaseController;
use FhSiteKit\FhskConfig\Form\ConfigForm;
use FhSiteKit\FhskConfig\Model\Config;
use FhSiteKit\FhskConfig\Model\ConfigTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

/**
 * Fhsk Module setup class
 */
class Module extends AbstractModule implements FormElementProviderInterface
{
    /**
     * Get module config
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
                    //  this is all handled by autoload_classmap,
                    //    never did figure out why it wiuldn't work here...
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
                'FhskConfigRegistry' => 'FhSiteKit\FhskConfig\Service\Config',
            ),
            'factories' => array(
                'FhskConfig' =>  function($sm) {
                    $config = $sm->get('FhskConfigRegistry');
                    $configTable = $sm->get('Config\Model\ConfigTable');
                    $config->setConfigTable($configTable);

                    return $config;
                },
                'Config\Model\ConfigEntity' =>  function($sm) {
                    $config = new Config();

                    return $config;
                },
                'Config\Model\ConfigTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype($sm->get('Config\Model\ConfigEntity'));

                    return new TableGateway('config', $dbAdapter, null, $resultSetPrototype);
                },
                'Config\Model\ConfigTable' =>  function($sm) {
                    $tableGateway = $sm->get('Config\Model\ConfigTableGateway');
                    $table = new ConfigTable($tableGateway);

                    return $table;
                },
            ),
        );
    }

    /**
     * Get form element config
     * @return array
     */
    public function getFormElementConfig()
    {
        return array(
            'factories' => array(
                'Config\Form\ConfigForm' => function($sm) {

                    return new ConfigForm();
                }
            )
        );
    }
}
