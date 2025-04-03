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

use FhSiteKit\FhskCore\Module\AbstractModule;
use FhSiteKit\FhskCore\Controller\BaseController;
use FhSiteKit\FhskConfig\Form\ConfigForm;
use FhSiteKit\FhskConfig\Model\Config;
use FhSiteKit\FhskConfig\Model\ConfigTable;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\FormElementProviderInterface;
use Laminas\Mvc\MvcEvent;

/**
 * Fhsk Config module setup class
 */
class Module extends AbstractModule implements FormElementProviderInterface
{
    /**
     * Attach listeners on bootstrap event
     *
     * Listens for "BaseController.collectViewData" event
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager()->getSharedManager();
        $eventManager->attach(
            '*',
            BaseController::EVENT_COLLECT_VIEW_DATA,
            function($e) use($sm) {
                //  we need to pass in the $sm, and call it now, rather than passing in the FhskConfig service
                //    because the service might have changed (UT mocking...) since bootstrap time
                $data = $sm->get('FhskConfig')->getConfigArray();
                $configViewData = array(
                    'FhskConfig' => array(
                        'data'      => $data,
                        'dataCount' => count($data) - count(array_keys($data, null, true)),
                        'keyCount'  => count($data),
                    ),
                );
                $target = $e->getTarget();
                $target->addViewData($configViewData);
            }
        );
    }

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
            'Laminas\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    'FhSiteKit\FhskConfig'             => __DIR__ . '/src/FhSiteKit/FhskConfig',
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
                    $dbAdapter = $sm->get('Laminas\Db\Adapter\Adapter');
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
