<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgPattern\Controller;

use FhskEntity\Controller\AdminController as FhskAdminController;

class AdminController extends FhskAdminController
{
    protected static $templateNamespace  = 'pattern';

    protected $patternTable;

    public function listAction()
    {
        $data = array(
            'patterns' => $this->getPatternTable()->fetchAll(),
        );
        $view = $this->generateViewModel($data, 'list');

        return $view;
    }

    public function getPatternTable()
    {
        if (! $this->patternTable) {
            $sm = $this->getServiceLocator();
            $this->patternTable = $sm->get('Pattern\Model\PatternTable');
        }
        return $this->patternTable;
    }
}