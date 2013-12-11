<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskEmail\Controller;

use FhSiteKit\FhskCore\Controller\EntityAdminController as FhskAdminController;
use FhSiteKit\FhskCore\Site as FhskSite;
use FhSiteKit\FhskEmail\Form\EmailForm;
use FhSiteKit\FhskEmail\Model\Email;
use FhSiteKit\FhskEmail\Model\EmailTableInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

/**
 * Email admin controller
 */
class AdminController extends FhskAdminController
{
    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'email';

    /**
     * The email table
     * @var EmailTableInterface
     */
    protected $emailTable;

    /**
     * Handle a list page request
     *
     * This shows only active patterns
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $emailService = $this->getServiceLocator()->get('Email\Service\EmailManager');
        $this->viewData = array(
            'emailArray' => $emailService->getEmailRegistry(),
        );
        $view = $this->generateViewModel('list');

        return $view;
    }

    /**
     * Handle a emailuration form page request or post submission
     *
     * @return \Zend\View\Model\ViewModel
     */  /*
    public function emailureAction()
    {
        $form = $this->getEmailForm();
        $form->get('submit')->setValue('Emailure');
        $emailService = $this->getServiceLocator()->get('FhskEmail');

        $emailKey = (string) $this->params()->fromRoute('emailKey', '');

        if (empty($emailKey)) {
            return $this->redirect()->toRoute('emailAdmin', array('siteKey' => FhskSite::getKey()));
        }

        if (! $emailService::hasKey($emailKey)) {
            $this->storeFlashMessage(
                sprintf('No module has registered the email key "%s"', $emailKey),
                FlashMessenger::NAMESPACE_ERROR
            );
            return $this->redirect()->toRoute('emailAdmin', array('siteKey' => FhskSite::getKey()));
        }

        $email = $emailService->getEmailFormattedByKey($emailKey);
        $form->setData($email->getArrayCopy());
        $form->get('email_value')->setLabel($email->email_key);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($email->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $email->exchangeArray($emailService->unformat($form->getData()));
                $email = $this->getEmailTable()->saveEmail($email);
                if ($email->email_value === null) {
                    $this->storeFlashMessage(
                        sprintf('Email "%s" not set', $email->email_key),
                        FlashMessenger::NAMESPACE_ERROR
                    );
                } else {
                    $this->storeFlashMessage(
                        sprintf('Email "%s" set', $email->email_key),
                        FlashMessenger::NAMESPACE_SUCCESS
                    );
                }
                // Redirect to list of emails

                return $this->redirect()->toRoute('emailAdmin', array('siteKey' => FhskSite::getKey()));
            }
        }

        $this->viewData = array(
            'form'       => $form,
            'formAction' => 'emailure',
            'emailKey'  => $emailKey,
        );
        $view = $this->generateViewModel('emailure');

        return $view;
    }  */

    /**
     * Handle an edit form page request or post submission
     * @return \Zend\View\Model\ViewModel|\Zend\Http\Response
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {

            return $this->redirect()->toRoute('emailAdmin', array(
                'action'  => 'create',
                'siteKey' => FhskSite::getKey(),
            ));
        }

        // Get the Email with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $email = $this->getemailTable()->getEmail($id);
        }
        catch (\Exception $ex) {
            $this->storeFlashMessage(
                sprintf('No email found with id %d', $id),
                FlashMessenger::NAMESPACE_ERROR
            );
            return $this->redirect()->toRoute('emailAdmin', array(
                'siteKey' => FhskSite::getKey(),
            ));
        }

        $form = $this->getEmailForm();
        $form->setData($email->getArrayCopy());
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($email->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $email = $this->getNewEmailEntity();
                $email->exchangeArray($form->getData());
                $this->getEmailTable()->saveEmail($email);
                $this->storeFlashMessage(
                    sprintf('Email %d (%s) updated', $email->id, $email->name),
                    FlashMessenger::NAMESPACE_SUCCESS
                );

                // Redirect to list of emails

                return $this->redirect()->toRoute('emailAdmin', array(
                    'siteKey' => FhskSite::getKey(),
                    'action'  => $this->params()->fromRoute('returnAction', ''),
                ));
            }
        }

        $this->viewData = array(
            'id'           => $id,
            'form'         => $form,
            'formAction'   => 'edit',
            'email'      => $email,
            'returnAction' => $this->params()->fromRoute('returnAction', ''),
        );
        $view = $this->generateViewModel('edit');

        return $view;
    }

    /**
     * Get the Email Table
     * @return EmailTableInterface
     * @throws \Exception
     */
    protected function getEmailTable()
    {
        if (! $this->emailTable) {
            $emailTable = $this->getServiceLocator()
                ->get('Email\Model\EmailTable');
            if (! $emailTable instanceof EmailTableInterface) {
                throw new \Exception(sprintf('Email table must be an instance of FhSiteKit\FhskEmail\Model\EmailTableInterface, "%s" given.', get_class($emailTable)));
            }
            $this->emailTable = $emailTable;
        }

        return $this->emailTable;
    }

    /**
     * Get the email form
     * @return EmailForm
     * @throws \Exception
     */
    protected function getEmailForm()
    {
        $form = $this->getServiceLocator()
            ->get('FormElementManager')
            ->get('Email\Form\EmailForm');
        if (! $form instanceof EmailForm) {
            throw new \Exception(sprintf('Email form must be an instance of FhSiteKit\FhskEmail\Form\EmailForm, "%s" given.', get_class($form)));
        }

        return $form;
    }

    /**
     * Get a new Email entity
     * @return Email
     * @throws \Exception
     */
    protected function getNewEmailEntity()
    {
        $email = $this->getServiceLocator()
            ->get('Email\Model\EmailEntity');
        if (! $email instanceof Email) {
            throw new \Exception(sprintf('New Email entity must be an instance of FhSiteKit\FhskEmail\Model\Email, "%s" given.', get_class($email)));
        }

        return $email;
    }
}
