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
use FhskSite\Core\Site as FhskSite;
use NdgPattern\Form\PatternForm;
use NdgPattern\Model\Pattern;

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

    public function addAction()
    {
        $form = new PatternForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $pattern = new Pattern();
            $form->setInputFilter($pattern->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $pattern->exchangeArray($form->getData());
                $this->getPatternTable()->savePattern($pattern);

                // Redirect to list of patterns
                return $this->redirect()->toRoute('patternAdmin', array('siteKey' => FhskSite::getKey()));
            }
        }

        $data = array(
            'form' => $form,
        );
        $view = $this->generateViewModel($data, 'add');

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