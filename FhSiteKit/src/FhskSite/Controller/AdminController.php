<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskSite\Controller;

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
        $data = array();
        $view = $this->generateViewModel($data, 'index');

        return $view;
    }

    /**
     * Generate ViewModel, fill it with child views
     *
     * Take care of menu in layout as well
     *
     * @param array $data
     * @param string $action
     * @return \Zend\View\Model\ViewModel
     */
    protected function generateViewModel($data, $action = null)
    {
        $data = $this->addRouteInfoToViewData($data);
        $data = $this->addFlashMessagesToViewData($data);

        $view = new ViewModel($data);
        $view->setTemplate($this->getTemplate('page', $action));

        $headlineView = new ViewModel($data);
        $headlineView->setTemplate($this->getTemplate('headline', $action));
        $view->addChild($headlineView, 'headline');

        $linksView = new ViewModel($data);
        $linksView->setTemplate($this->getTemplate('links', $action));
        $view->addChild($linksView, 'links');

        $dataView = new ViewModel($data);
        $dataView->setTemplate($this->getTemplate('data', $action));
        $view->addChild($dataView, 'data');

        if (! empty($data['form'])) {
            $formView = new ViewModel($data);
            $formView->setTemplate($this->getTemplate('form', $action));
            $view->addChild($formView, 'form');
        }

        if (! empty($data['messages'])) {
            $messagesView = new ViewModel($data);
            $messagesView->setTemplate($this->getTemplate('messages', $action, 'admin', 'site'));
            $view->addChild($messagesView, 'messages');
        }

        //  Take care of menu in layout as well
        $menuView = new ViewModel($data);
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
     * Add flash messages to view data
     * @param array $data
     * @param string $namespace
     * @return array
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
     * @param array $data
     * @return array
     */
    protected function addFlashMessagesToViewData($data)
    {
        if ($this->flashMessenger()->hasInfoMessages()) {
            $data['messages']['info'] = $this->flashMessenger()->getInfoMessages();
        }
        if ($this->flashMessenger()->hasSuccessMessages()) {
            $data['messages']['success'] = $this->flashMessenger()->getSuccessMessages();
        }
        if ($this->flashMessenger()->hasErrorMessages()) {
            $data['messages']['warning'] = $this->flashMessenger()->getErrorMessages();
        }

        return $data;
    }

    /**
     * Add namespace, controller and action info to template $data array
     * @param array $data
     * @return array
     */
    protected function addRouteInfoToViewData($data)
    {
        $data['templateNamespace']  = static::$templateNamespace;
        $data['templateController'] = static::$templateController;
        $data['routeAction']        = $this->params()->fromRoute('action');

        return $data;
    }
}
