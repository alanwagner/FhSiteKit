<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgTemplate\Controller;

use FhSiteKit\FhskCore\FhskEntity\Controller\AdminController as FhskAdminController;
use FhSiteKit\FhskCore\FhskSite\Core\Site as FhskSite;
use Ndg\NdgTemplate\Form\TemplateForm;
use Ndg\NdgTemplate\Model\Template;
use Ndg\NdgTemplate\Model\TemplateTableInterface;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

/**
 * Template admin controller
 */
class AdminController extends FhskAdminController
{
    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'template';

    /**
     * The template table
     * @var TemplateTableInterface
     */
    protected $templateTable;

    /**
     * Handle a list page request
     *
     * This shows only active templates
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $this->viewData = array(
            'templatesData' => $this->getTemplateTable()->fetchDataWithPatternByIsArchived(0)
        );
        $view = $this->generateViewModel('list');

        return $view;
    }

    /**
     * Handle a request for a list of archived templates
     * @return \Zend\View\Model\ViewModel
     */
    public function listArchivedAction()
    {
        $this->viewData = array(
            'templatesData' => $this->getTemplateTable()->fetchDataWithPatternByIsArchived(1),
            'isArchiveList' => true,
        );
        $view = $this->generateViewModel('list');

        return $view;
    }

    /**
     * Handle an add form page request or post submission
     *
     * Pre-populate the form if a template id param is set (cloning)
     *
     * @return \Zend\View\Model\ViewModel|\Zend\Http\Response
     */
    public function createAction()
    {
        $form = $this->getTemplateForm();
        $form->get('submit')->setValue('Create');

        //  If cloning, populate form with values from selected Template
        $id = (int) $this->params()->fromRoute('id', 0);
        if (! empty($id)) {
            // Get the Template with the specified id.  An exception is thrown
            // if it cannot be found, in which case go to the index page.
            try {
                $template = $this->getTemplateTable()->getTemplate($id);
            }
            catch (\Exception $ex) {
                $this->storeFlashMessage(
                    sprintf('No template found with id %d', $id),
                    FlashMessenger::NAMESPACE_ERROR
                );
                return $this->redirect()->toRoute('templateAdmin', array('siteKey' => FhskSite::getKey()));
            }

            $cloneData = $template->getArrayCopy();
            $cloneData['id'] = null;
            $form->setData($cloneData);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $template = $this->getNewTemplateEntity();
            $form->setInputFilter($template->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $template->exchangeArray($form->getData());
                $template = $this->getTemplateTable()->saveTemplate($template);
                $this->storeFlashMessage(
                    sprintf('Template %d (%s) created', $template->id, $template->name),
                    FlashMessenger::NAMESPACE_SUCCESS
                );

                // Redirect to list of templates

                return $this->redirect()->toRoute('templateAdmin', array('siteKey' => FhskSite::getKey()));
            }
        }

        $this->viewData = array(
            'form'       => $form,
            'formAction' => 'create',
        );
        $view = $this->generateViewModel('create');

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

            return $this->redirect()->toRoute('templateAdmin', array(
                'action'  => 'create',
                'siteKey' => FhskSite::getKey(),
            ));
        }

        // Get the Template with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $template = $this->gettemplateTable()->getTemplate($id);
        }
        catch (\Exception $ex) {
            $this->storeFlashMessage(
                sprintf('No template found with id %d', $id),
                FlashMessenger::NAMESPACE_ERROR
            );
            return $this->redirect()->toRoute('templateAdmin', array(
                'siteKey' => FhskSite::getKey(),
            ));
        }

        $form = $this->getTemplateForm();
        $form->bind($template);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($template->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getTemplateTable()->saveTemplate($template);
                $this->storeFlashMessage(
                    sprintf('Template %d (%s) updated', $template->id, $template->name),
                    FlashMessenger::NAMESPACE_SUCCESS
                );

                // Redirect to list of templates

                return $this->redirect()->toRoute('templateAdmin', array(
                    'siteKey' => FhskSite::getKey(),
                    'action'  => $this->params()->fromRoute('returnAction', ''),
                ));
            }
        }

        $this->viewData = array(
            'id'         => $id,
            'form'       => $form,
            'formAction' => 'edit',
            'template'    => $template,
            'returnAction' => $this->params()->fromRoute('returnAction', ''),
        );
        $view = $this->generateViewModel('edit');

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
            // Get the Template with the specified id.  An exception is thrown
            // if it cannot be found, in which case go to the index page.
            try {
                $template = $this->getTemplateTable()->getTemplate($id);
            }
            catch (\Exception $ex) {
                $this->storeFlashMessage(
                    sprintf('No template found with id %d', $id),
                    FlashMessenger::NAMESPACE_ERROR
                );
                return $this->redirect()->toRoute('templateAdmin', array('siteKey' => FhskSite::getKey()));
            }

            $template->is_archived = empty($template->is_archived) ? 1 : 0;
            $this->getTemplateTable()->saveTemplate($template);
            $this->storeFlashMessage(
                sprintf('Template %d (%s) %s', $template->id, $template->name, (empty($template->is_archived) ? 'unarchived' : 'archived')),
                FlashMessenger::NAMESPACE_SUCCESS
            );
        }

        return $this->redirect()->toRoute('templateAdmin', array('siteKey' => FhskSite::getKey(), 'action' => $this->params()->fromRoute('returnAction', '')));
    }

    /**
     * Get the Template Table
     * @return TemplateTableInterface
     */
    protected function getTemplateTable()
    {
        if (! $this->templateTable) {
            $templateTable = $this->getServiceLocator()
                ->get('Template\Model\TemplateTable');
            if (! $templateTable instanceof TemplateTableInterface) {
                throw new \Exception(sprintf('Template table must be an instance of Ndg\NdgTemplate\Model\TemplateTableInterface, "%s" given.', get_class($templateTable)));
            }
            $this->templateTable = $templateTable;
        }

        return $this->templateTable;
    }

    /**
     * Get the template form
     * @return TemplateForm
     * @throws \Exception
     */
    protected function getTemplateForm()
    {
        $form = $this->getServiceLocator()
            ->get('FormElementManager')
            ->get('Template\Form\TemplateForm');
        if (! $form instanceof TemplateForm) {
            throw new \Exception(sprintf('Template form must be an instance of Ndg\NdgTemplate\Form\TemplateForm, "%s" given.', get_class($form)));
        }

        return $form;
    }

    /**
     * Get a new Template entity
     * @return Template
     * @throws \Exception
     */
    protected function getNewTemplateEntity()
    {
        $template = $this->getServiceLocator()
            ->get('Template\Model\TemplateEntity');
        if (! $template instanceof Template) {
            throw new \Exception(sprintf('New Template entity must be an instance of Ndg\NdgTemplate\Model\Template, "%s" given.', get_class($template)));
        }

        return $template;
    }
}
