<?php
/**
 * Farther Horizon Site Kit
 *
 * @link      http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2014 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskDemo\Site\Controller;

use FhSiteKit\FhskCore\Controller\BaseActionController;
use Zend\View\Model\ViewModel;

class IndexController extends BaseActionController
{
    /**
     * Component string identifier
     * @var string
     */
    protected static $componentString  = 'site';

    /**
     * Controller string identifier
     * @var string
     */
    protected static $controllerString = 'index';

    /**
     * Respond to Index page request
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $view = $this->generateViewModel('index');

        return $view;
    }
}
