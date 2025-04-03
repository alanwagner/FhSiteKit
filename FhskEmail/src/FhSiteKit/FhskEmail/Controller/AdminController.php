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
use FhSiteKit\FhskEmail\Service\EmailManager;
use Laminas\Form\FormInterface;
use Laminas\Mvc\Controller\Plugin\FlashMessenger;

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
     * The email manager
     * @var EmailManager
     */
    protected $emailManager;

    /**
     * Handle a list page request
     *
     * This shows only active patterns
     *
     * @return \Laminas\View\Model\ViewModel
     */
    public function listAction()
    {
        $emailService = $this->getServiceLocator()->get('Email\Service\EmailManager');
        $this->viewData = array(
            'emailArray' => $this->getEmailManager()->getEmailRegistry(),
        );
        $view = $this->generateViewModel('list');

        return $view;
    }

    /**
     * Handle an edit form page request or post submission
     * @return \Laminas\View\Model\ViewModel|\Laminas\Http\Response
     */
    public function editAction()
    {
        $emailKey = $this->params()->fromRoute('emailKey', '');
        if (!$emailKey) {

            return $this->redirect()->toRoute('emailAdmin', array(
                'siteKey' => FhskSite::getKey(),
            ));
        }

        // Get the Email with the specified id.  We get null
        // if it cannot be found, in which case go to the index page.
        $email = $this->getEmailTable()->fetchEmailByKey($emailKey);

        if ($email == null) {
            $email = $this->getNewEmailEntity();
            $email->exchangeArray(array('key' => $emailKey));
        }

        $form = $this->getEmailForm();
        $form->setData($email->getArrayCopy());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($email->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $email = $this->getNewEmailEntity();
                $email->exchangeArray($form->getData());
                $this->getEmailTable()->saveEmail($email);
                $this->storeFlashMessage(
                    sprintf('Email %s updated', $email->key),
                    FlashMessenger::NAMESPACE_SUCCESS
                );

                // Redirect to list of emails

                return $this->redirect()->toRoute('emailAdmin', array(
                    'siteKey' => FhskSite::getKey(),
                ));
            }
        }

        $this->viewData = array(
            'emailKey'    => $emailKey,
            'form'        => $form,
            'formAction'  => 'edit',
            'emailConfig' => $this->getEmailManager()->getEmailRegistryByKey($emailKey),
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
     * Get the Email Manager
     * @return EmailManagerInterface
     * @throws \Exception
     */
    protected function getEmailManager()
    {
        if (! $this->emailManager) {
            $emailManager = $this->getServiceLocator()
                ->get('Email\Service\EmailManager');
            if (! $emailManager instanceof EmailManager) {
                throw new \Exception(sprintf('Email manager must be an instance of FhSiteKit\FhskEmail\Service\EmailManager, "%s" given.', get_class($emailManager)));
            }
            $this->emailManager = $emailManager;
        }

        return $this->emailManager;
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
