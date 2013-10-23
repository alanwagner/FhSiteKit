<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskSite\Controller;

use FhskEntity\Controller\AdminController as FhskAdminController;

class AdminController extends FhskAdminController
{
    protected static $templateNamespace  = 'site';

    public function indexAction()
    {
        $data = array();
        $view = $this->generateViewModel($data, 'index');

        return $view;
    }
}
