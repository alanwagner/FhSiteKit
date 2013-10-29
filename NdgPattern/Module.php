<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgPattern;

use FhSiteKit\AbstractModule;
use Ndg\NdgPattern\Form\PatternForm;
use Ndg\NdgPattern\Model\Pattern;
use Ndg\NdgPattern\Model\PatternTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

/**
 * NdgPattern Module setup class
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
                    'Ndg\NdgPattern' => __DIR__ . '/src/Ndg/NdgPattern',
                ),
            ),
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
                'Pattern\Model\PatternEntity' =>  function($sm) {
                    $pattern = new Pattern();

                    return $pattern;
                },
                'Pattern\Model\PatternTable' =>  function($sm) {
                    $tableGateway = $sm->get('Pattern\Model\PatternTableGateway');
                    $table = new PatternTable($tableGateway);

                    return $table;
                },
                'Pattern\Model\PatternTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype($sm->get('Pattern\Model\PatternEntity'));

                    return new TableGateway('pattern', $dbAdapter, null, $resultSetPrototype);
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
                'Pattern\Form\PatternForm' => function($sm) {

                    return new PatternForm();
                }
            )
        );
    }
}
