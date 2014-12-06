<?php
/**
 * Farther Horizon Site Kit
 *
 * @link      http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2014 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Base controller
 *
 * Provides core, system-wide props and methods for handling view data
 */
class BaseActionController extends AbstractActionController
{
    /**
     * Component string identifier
     * @var string
     */
    protected static $componentString  = 'fhsk';

    /**
     * Controller string identifier
     * @var string
     */
    protected static $controllerString = 'base';

    /**
     * View data
     * @var array
     */
    protected $viewData = array();

    /**
     * Generate ViewModel, fill it with data and child views
     * Send data to layout
     *
     * @param string $action
     * @return \Zend\View\Model\ViewModel
     */
    protected function generateViewModel($action = null)
    {
        $this->prepareViewData();
        $view = new ViewModel($this->viewData);
        $view->setTemplate($this->getTemplate('content', $action));
        $this->sendDataToLayout();

        return $view;
    }

    /**
     * Prepare viewData before sending to ViewModel
     */
    protected function prepareViewData()
    {
        if (empty($this->viewData['componentString'])) {
            $this->viewData['componentString'] = static::$componentString;
        }
        if (empty($this->viewData['controllerString'])) {
            $this->viewData['controllerString'] = static::$controllerString;
        }
        $this->viewData['routeAction'] = $this->params()->fromRoute('action');
    }

    /**
     * Send viewData as needed to layout
     */
    protected function sendDataToLayout()
    {
        $this->layout()->componentString  = $this->viewData['componentString'];
        $this->layout()->controllerString = $this->viewData['controllerString'];
        $this->layout()->routeAction      = $this->viewData['routeAction'];
    }

    /**
     * Get the template string
     *
     * Looks for
     *    component/controller/block-action
     * or component/controller/block
     * or 'site'/controller/block-action
     * or 'site'/controller/block
     *
     * @param string $block
     * @param string $action
     * @param string $controller
     * @param string $component
     * @return string|null
     */
    protected function getTemplate($block, $action = null, $controller = null, $component = null)
    {
        if (empty($controller)) {
            $controller = static::$controllerString;
        }
        if (empty($component)) {
            $component = static::$componentString;
        }
        $templatePathStack = $this->getServiceLocator()->get('Zend\View\Resolver\TemplatePathStack');

        if (!empty($action)) {
            $template = sprintf(
                '%s/%s/%s-%s',
                $component,
                $controller,
                $block,
                $action
            );
            if ($templatePathStack->resolve($template)) {

                return $template;
            }
        }
        $template = sprintf(
            '%s/%s/%s',
            $component,
            $controller,
            $block
        );
        if ($templatePathStack->resolve($template)) {

            return $template;
        }

        $template = sprintf(
            '%s/%s/%s-%s',
            'site',
            $controller,
            $block,
            $action
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
}
