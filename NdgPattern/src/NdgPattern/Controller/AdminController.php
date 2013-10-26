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
use NdgPattern\Model\PatternTableInterface;

/**
 * Pattern admin controller
 */
class AdminController extends FhskAdminController
{
    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'pattern';

    /**
     * The pattern table
     * @var PatternTableInterface
     */
    protected $patternTable;

    /**
     * Handle a list page request
     *
     * This shows only active patterns
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $data = array(
            'patterns' => $this->getPatternTable()->fetchByIsArchived(0)
        );
        $view = $this->generateViewModel($data, 'list');

        return $view;
    }

    /**
     * Handle a request for a list of archived patterns
     * @return \Zend\View\Model\ViewModel
     */
    public function listArchivedAction()
    {
        $data = array(
            'patterns'      => $this->getPatternTable()->fetchByIsArchived(1),
            'isArchiveList' => true,
        );
        $view = $this->generateViewModel($data, 'list');

        return $view;
    }

    /**
     * Handle an add form page request or post submission
     *
     * Pre-populate the form if a pattern id param is set (cloning)
     *
     * @return \Zend\View\Model\ViewModel|\Zend\Http\Response
     */
    public function addAction()
    {
        $form = new PatternForm();
        $form->get('submit')->setValue('Add');

        //  If cloning, populate form with values from selected Pattern
        $id = (int) $this->params()->fromRoute('id', 0);
        if (! empty($id)) {
            // Get the Pattern with the specified id.  An exception is thrown
            // if it cannot be found, in which case go to the index page.
            try {
                $pattern = $this->getPatternTable()->getPattern($id);
            }
            catch (\Exception $ex) {
                return $this->redirect()->toRoute('patternAdmin', array('siteKey' => FhskSite::getKey()));
            }

            $cloneData = $pattern->getArrayCopy();
            $cloneData['id'] = null;
            $form->setData($cloneData);
        }

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
            'form'       => $form,
            'formAction' => 'add',
        );
        $view = $this->generateViewModel($data, 'add');

        return $view;
    }

    /**
     * Handle an edit form page request or post submission
     * @return \Zend\View\Model\ViewModel|\Zend\Http\Response
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {

            return $this->redirect()->toRoute('patternAdmin', array(
                'action'  => 'add',
                'siteKey' => FhskSite::getKey(),
            ));
        }

        // Get the Pattern with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $pattern = $this->getpatternTable()->getPattern($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('patternAdmin', array(
                'siteKey' => FhskSite::getKey(),
            ));
        }

        $form  = new patternForm();
        $form->bind($pattern);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($pattern->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getPatternTable()->savePattern($pattern);

                // Redirect to list of patterns

                return $this->redirect()->toRoute('patternAdmin', array(
                    'siteKey' => FhskSite::getKey(),
                ));
            }
        }

        $data = array(
            'id'         => $id,
            'form'       => $form,
            'formAction' => 'edit',
            'pattern'    => $pattern,
        );
        $view = $this->generateViewModel($data, 'edit');

        return $view;
    }

    /**
     * Handle a toggleArchived page request
     * @return \Zend\Http\Response
     */
    public function toggleArchivedAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (! empty($id)) {
            // Get the Pattern with the specified id.  An exception is thrown
            // if it cannot be found, in which case go to the index page.
            try {
                $pattern = $this->getPatternTable()->getPattern($id);
            }
            catch (\Exception $ex) {
                return $this->redirect()->toRoute('patternAdmin', array('siteKey' => FhskSite::getKey()));
            }

            $pattern->is_archived = empty($pattern->is_archived) ? 1 : 0;
            $this->getPatternTable()->savePattern($pattern);
        }

        return $this->redirect()->toRoute('patternAdmin', array('siteKey' => FhskSite::getKey(), 'action' => $this->params()->fromRoute('returnAction', '')));
    }

    /**
     * Get the Pattern Table
     * @return PatternTableInterface
     */
    protected function getPatternTable()
    {
        if (! $this->patternTable) {
            $sm = $this->getServiceLocator();
            $this->patternTable = $sm->get('Pattern\Model\PatternTable');
        }

        return $this->patternTable;
    }
}
