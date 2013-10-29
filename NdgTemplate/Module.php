<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgTemplate;

use FhSiteKit\AbstractModule;
use FhSiteKit\FhskCore\FhskEntity\Model\RowData;
use Ndg\NdgTemplate\Form\TemplateForm;
use Ndg\NdgTemplate\Model\Template;
use Ndg\NdgTemplate\Model\TemplateTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * NdgTemplate Module setup class
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
                    'Ndg\NdgTemplate' => __DIR__ . '/src/Ndg/NdgTemplate',
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
            'Ndg\NdgPattern',
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
                'Template\Model\TemplateEntity' =>  function($sm) {
                    $template = new Template();

                    return $template;
                },
                'Template\Model\TemplateTable' =>  function($sm) {
                    $tableGateway = $sm->get('Template\Model\TemplateTableGateway');
                    $rowDataTableGateway = $sm->get('Template\Model\TemplateRowDataTableGateway');
                    $patternTable = $sm->get('Pattern\Model\PatternTable');
                    $table = new TemplateTable($tableGateway, $patternTable, $rowDataTableGateway);

                    return $table;
                },
                'Template\Model\TemplateTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype($sm->get('Template\Model\TemplateEntity'));

                    return new TableGateway('template', $dbAdapter, null, $resultSetPrototype);
                },
                'Template\Model\TemplateRowDataTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RowData());

                    return new TableGateway('template', $dbAdapter, null, $resultSetPrototype);
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
                'Template\Form\TemplateForm' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $patternTable = $serviceLocator->get('Pattern\Model\PatternTable');
                    $form = new TemplateForm($patternTable);

                    return $form;
                }
            )
        );
    }
}
