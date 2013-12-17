<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Base controller
 *
 * Provides system-wide functions for handling view data
 */
class BaseController extends AbstractActionController
{
    /**
     * Even broadcast when collecting final viewData
     * @var string
     */
    const EVENT_COLLECT_VIEW_DATA = "BaseController.collectViewData";

    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'fhsk';

    /**
     * Template controller
     * @var string
     */
    protected static $templateController = 'base';

    /**
     * Layout template
     * @var string
     */
    protected static $layoutTemplate     = 'layout/layout';

    /**
     * View data
     * @var array
     */
    protected $viewData = array();

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException
     */
    public function onDispatch(MvcEvent $e)
    {
        $layout = $this->layout();
        $layout->setTemplate(static::$layoutTemplate);

        return parent::onDispatch($e);
    }

    /**
     * Handle an index page request
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $view = $this->generateViewModel('index');

        return $view;
    }

    public function addViewData($viewData)
    {
        foreach ($viewData as $key => $data) {
            $this->viewData[$key] = $data;
        }
    }

    /**
     * Generate ViewModel, fill it with child views
     *
     * Take care of menu in layout as well
     *
     * @param string $action
     * @return \Zend\View\Model\ViewModel
     */
    protected function generateViewModel($action = null)
    {
        $this->addAdditionalViewData();

        $view = new ViewModel($this->viewData);
        $view->setTemplate($this->getTemplate('page', $action));

        return $view;
    }

    protected function addAdditionalViewData()
    {
        $this->addRouteInfoToViewData();
        $this->addFlashMessagesToViewData();

        $this->triggerCollectViewDataEvent();
    }

    /**
     * Trigger "BaseController.collectViewData" event
     */
    protected function triggerCollectViewDataEvent()
    {
        $this->getEventManager()->trigger(self::EVENT_COLLECT_VIEW_DATA, $this);
    }

    /**
     * Get the template string
     *
     * Looks for
     *    namespace/controller/block-action
     * or namespace/controller/block-'form'  (if action is 'create' or 'edit')
     * or namespace/controller/block
     * or 'site'/controller/block
     *
     * @param string $block
     * @param string $action
     * @param string $controller
     * @param string $namespace
     * @return string|null
     */
    protected function getTemplate($block, $action = null, $controller = null, $namespace = null)
    {
        if (empty($controller)) {
            $controller = static::$templateController;
        }
        if (empty($namespace)) {
            $namespace = static::$templateNamespace;
        }

        $templatePathStack = $this->getServiceLocator()->get('Zend\View\Resolver\TemplatePathStack');

        if (!empty($action)) {
            $template = sprintf(
                '%s/%s/%s-%s',
                $namespace,
                $controller,
                $block,
                $action
            );
            if ($templatePathStack->resolve($template)) {

                return $template;
            }
            if (in_array($action, array('create', 'edit'))) {
                $template = sprintf(
                    '%s/%s/%s-%s',
                    $namespace,
                    $controller,
                    $block,
                    'form'
                );
                if ($templatePathStack->resolve($template)) {

                    return $template;
                }
            }
        }
        $template = sprintf(
            '%s/%s/%s',
            $namespace,
            $controller,
            $block
        );
        if ($templatePathStack->resolve($template)) {

            return $template;
        }

        $template = sprintf(
            '%s/%s/%s',
            'site',
            $controller,
            $block
        );
        if ($templatePathStack->resolve($template)) {

            return $template;
        }

        return null;
    }

    /**
     * Add flash messages to storage
     * @param string $message
     * @param string $namespace
     */
    protected function storeFlashMessage($message, $namespace = FlashMessenger::NAMESPACE_INFO)
    {
        $flashMessenger = $this->flashMessenger();
        switch ($namespace) {
            case FlashMessenger::NAMESPACE_SUCCESS:
                $flashMessenger->addSuccessMessage($message);
                break;
            case FlashMessenger::NAMESPACE_ERROR:
                $flashMessenger->addErrorMessage($message);
                break;
            case FlashMessenger::NAMESPACE_INFO:
            default:
                $flashMessenger->addInfoMessage($message);
                break;
        }
    }

    /**
     * Add flash messages to view data
     */
    protected function addFlashMessagesToViewData()
    {
        if ($this->flashMessenger()->hasInfoMessages()) {
            foreach ($this->flashMessenger()->getInfoMessages() as $infoMessage) {
                $this->viewData['messages']['info'][] = $infoMessage;
            }
        }
        if ($this->flashMessenger()->hasSuccessMessages()) {
            foreach ($this->flashMessenger()->getSuccessMessages() as $successMessage) {
                $this->viewData['messages']['success'][] = $successMessage;
            }
        }
        if ($this->flashMessenger()->hasErrorMessages()) {
            foreach ($this->flashMessenger()->getErrorMessages() as $errorMessage) {
                $this->viewData['messages']['warning'][] = $errorMessage;
            }
        }
    }

    /**
     * Add namespace, controller and action info to view data
     */
    protected function addRouteInfoToViewData()
    {
        $this->viewData['templateNamespace']  = static::$templateNamespace;
        $this->viewData['templateController'] = static::$templateController;
        $this->viewData['routeAction']        = $this->params()->fromRoute('action');
    }

    /**
     * Get initial part of url
     * @return string
     */
    protected function httpDomain()
    {
        $uri = $this->getRequest()->getUri();

        return $uri->getScheme() . '://' . $uri->getHost();
    }
}
