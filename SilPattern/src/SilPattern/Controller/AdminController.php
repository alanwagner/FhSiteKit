<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace SilPattern\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{
    protected $patternTable;

    public function listAction()
    {
        return new ViewModel(array(
            'patterns' => $this->getPatternTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
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