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

use FhSiteKit\FhskCore\Base\Beautifier\BaseController as VitaminBaseController;
use Zend\View\Model\ViewModel;

/**
 * Base admin controller
 *
 * Serves for pages like admin home that do not handle any entity
 */
class AdminController extends VitaminBaseController
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
     * Generate ViewModel, fill it with child views
     *
     * Take care of menu in layout as well
     *
     * @param string $action
     * @return \Zend\View\Model\ViewModel
     */
    protected function generateViewModel($action = null)
    {
        $view = parent::generateViewModel($action);

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
}
