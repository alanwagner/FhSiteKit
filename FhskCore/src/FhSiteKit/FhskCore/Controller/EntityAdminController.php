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

use FhSiteKit\FhskCore\Controller\AdminController as FhskAdminController;

/**
 * Base entity admin controller
 */
class EntityAdminController extends FhskAdminController
{
    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'entity';

    /**
     * Handle a list page request
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction() {
        $view = $this->generateViewModel('list');

        return $view;
    }

    /**
     * Handle an add form page request or post submission
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction() {
        $view = $this->generateViewModel('create');

        return $view;
    }
}
