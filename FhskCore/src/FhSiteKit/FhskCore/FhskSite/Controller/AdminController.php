<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\FhskSite\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Base admin controller
 *
 * Serves for pages like admin home that do not handle any entity
 */
class AdminController extends AbstractActionController
{
    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'site';

    /**
     * Template controller
     * @var string
     */
    protected static $templateController = 'admin';

    /**
     * Layout template
     * @var string
     */
    protected static $layoutTemplate     = 'layout/admin';

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
        $this->addRouteInfoToViewData();
        $this->addFlashMessagesToViewData();

        $view = new ViewModel($this->viewData);
        $view->setTemplate($this->getTemplate('page', $action));

        $headlineView = new ViewModel($this->viewData);
        $headlineView->setTemplate($this->getTemplate('headline', $action));
        $view->addChild($headlineView, 'headline');

        $linksView = new ViewModel($this->viewData);
        $linksView->setTemplate($this->getTemplate('links', $action));
        $view->addChild($linksView, 'links');

        $dataView = new ViewModel($this->viewData);
        $dataView->setTemplate($this->getTemplate('data', $action));
        $view->addChild($dataView, 'data');

        if (! empty($this->viewData['form'])) {
            $formView = new ViewModel($this->viewData);
            $formView->setTemplate($this->getTemplate('form', $action));
            $view->addChild($formView, 'form');
        }

        if (! empty($this->viewData['messages'])) {
            $messagesView = new ViewModel($this->viewData);
            $messagesView->setTemplate($this->getTemplate('messages', $action, 'admin', 'site'));
            $view->addChild($messagesView, 'messages');
        }

        //  Take care of menu in layout as well
        $menuView = new ViewModel($this->viewData);
        $menuView->setTemplate($this->getTemplate('menu', $action, 'admin', 'site'));
        $this->layout()->addChild($menuView, 'menu');

        return $view;
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
            $this->viewData['messages']['info'] = $this->flashMessenger()->getInfoMessages();
        }
        if ($this->flashMessenger()->hasSuccessMessages()) {
            $this->viewData['messages']['success'] = $this->flashMessenger()->getSuccessMessages();
        }
        if ($this->flashMessenger()->hasErrorMessages()) {
            $this->viewData['messages']['warning'] = $this->flashMessenger()->getErrorMessages();
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
}
