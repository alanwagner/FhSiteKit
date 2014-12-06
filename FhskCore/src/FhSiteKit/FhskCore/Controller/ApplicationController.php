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

class ApplicationController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
