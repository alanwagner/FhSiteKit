<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgNetwork;

use FhSiteKit\FhskCore\AbstractModule;
//use Ndg\NdgNetwork\NdgEdge\Model\Edge;
//use Ndg\NdgNetwork\NdgEdge\Model\EdgeTable;
use Ndg\NdgNetwork\NdgInstance\Model\Instance;
use Ndg\NdgNetwork\NdgInstance\Model\InstanceTable;
//use Ndg\NdgNetwork\NdgNode\Model\Node;
//use Ndg\NdgNetwork\NdgNode\Model\NodeTable;
use Ndg\NdgNetwork\Service\NetworkManager;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * NdgPattern Module setup class
 */
class Module extends AbstractModule
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
                    'Ndg\NdgNetwork'             => __DIR__ . '/src/Ndg/NdgNetwork',
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
            'factories' => array(
                'NdgNetworkManager' =>  function($sm) {
                    $networkManager = new NetworkManager($sm);

                    return $networkManager;
                },
                'Instance\Model\InstanceEntity' =>  function($sm) {
                    $instance = new Instance();

                    return $instance;
                },
                'Instance\Model\InstanceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype($sm->get('Instance\Model\InstanceEntity'));

                    return new TableGateway('instance', $dbAdapter, null, $resultSetPrototype);
                },
                'Instance\Model\InstanceTable' =>  function($sm) {
                    $tableGateway = $sm->get('Instance\Model\InstanceTableGateway');
                    $table = new InstanceTable($tableGateway);

                    return $table;
                },
            ),
        );
    }
}
