<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskEmail;

use FhSiteKit\FhskCore\Module\AbstractModule;
use FhSiteKit\FhskCore\Controller\BaseController;
use FhSiteKit\FhskEmail\Form\EmailForm;
use FhSiteKit\FhskEmail\Model\Email;
use FhSiteKit\FhskEmail\Model\EmailTable;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Mail\Transport\Sendmail as SendmailTransport;
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
    {/*
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
*/    }

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
                    'FhSiteKit\FhskEmail' => __DIR__ . '/src/FhSiteKit/FhskEmail',
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
                'FhskEmailRegistry' => 'FhSiteKit\FhskEmail\Service\EmailManager',
            ),
            'factories' => array(
                'Email\Service\EmailManager' =>  function($sm) {
                    $emailManager = $sm->get('FhskEmailRegistry');
                    $emailManager->setServiceLocator($sm);

                    return $emailManager;
                },
                'FhSiteKit\EmailTransport' => function($sm) {

                    return new SendmailTransport();
                },
                'Email\Model\EmailEntity' =>  function($sm) {
                    $email = new Email();

                    return $email;
                },
                'Email\Model\EmailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Laminas\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype($sm->get('Email\Model\EmailEntity'));

                    return new TableGateway('email', $dbAdapter, null, $resultSetPrototype);
                },
                'Email\Model\EmailTable' =>  function($sm) {
                    $tableGateway = $sm->get('Email\Model\EmailTableGateway');
                    $table = new EmailTable($tableGateway);

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
                'Email\Form\EmailForm' => function($sm) {

                    return new EmailForm();
                }
            )
        );
    }
}
