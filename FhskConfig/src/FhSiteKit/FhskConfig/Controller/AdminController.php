<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskConfig\Controller;

use FhSiteKit\FhskCore\FhskEntity\Controller\AdminController as FhskAdminController;
use FhSiteKit\FhskCore\FhskSite\Site as FhskSite;
use FhSiteKit\FhskConfig\Form\ConfigForm;
use FhSiteKit\FhskConfig\Model\Config;
use FhSiteKit\FhskConfig\Model\ConfigTableInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

/**
 * Config admin controller
 */
class AdminController extends FhskAdminController
{
    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'config';

    /**
     * The config table
     * @var ConfigTableInterface
     */
    protected $configTable;

    /**
     * Handle a list page request
     *
     * This shows only active patterns
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $configService = $this->getServiceLocator()->get('FhskConfig');
        $this->viewData = array(
            'configArray' => $configService->getConfigArrayFormatted(),
        );
        $view = $this->generateViewModel('list');

        return $view;
    }

    /**
     * Handle a configuration form page request or post submission
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function configureAction()
    {
        $form = $this->getConfigForm();
        $form->get('submit')->setValue('Configure');
        $configService = $this->getServiceLocator()->get('FhskConfig');

        $configKey = (string) $this->params()->fromRoute('configKey', '');

        if (empty($configKey)) {
            return $this->redirect()->toRoute('configAdmin', array('siteKey' => FhskSite::getKey()));
        }

        if (! $configService::hasKey($configKey)) {
            $this->storeFlashMessage(
                sprintf('No module has registered the config key "%s"', $configKey),
                FlashMessenger::NAMESPACE_ERROR
            );
            return $this->redirect()->toRoute('configAdmin', array('siteKey' => FhskSite::getKey()));
        }

        $config = $configService->getConfigFormattedByKey($configKey);
        $form->setData($config->getArrayCopy());
        $form->get('config_value')->setLabel($config->config_key);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($config->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $config->exchangeArray($configService->unformat($form->getData()));
                $config = $this->getConfigTable()->saveConfig($config);
                if ($config->config_value === null) {
                    $this->storeFlashMessage(
                        sprintf('Config "%s" not set', $config->config_key),
                        FlashMessenger::NAMESPACE_ERROR
                    );
                } else {
                    $this->storeFlashMessage(
                        sprintf('Config "%s" set', $config->config_key),
                        FlashMessenger::NAMESPACE_SUCCESS
                    );
                }
                // Redirect to list of configs

                return $this->redirect()->toRoute('configAdmin', array('siteKey' => FhskSite::getKey()));
            }
        }

        $this->viewData = array(
            'form'       => $form,
            'formAction' => 'configure',
            'configKey'  => $configKey,
        );
        $view = $this->generateViewModel('configure');

        return $view;
    }

    /**
     * Handle an add form page request or post submission
     *
     * Pre-populate the form if a config id param is set (cloning)
     *
     * @return \Zend\View\Model\ViewModel|\Zend\Http\Response
     */
    public function addAction()
    {
        $form = $this->getConfigForm();
        $form->get('submit')->setValue('Create');

        //  If cloning, populate form with values from selected Config
        $id = (int) $this->params()->fromRoute('id', 0);
        if (! empty($id)) {
            // Get the Config with the specified id.  An exception is thrown
            // if it cannot be found, in which case go to the index page.
            try {
                $config = $this->getConfigTable()->getConfig($id);
            }
            catch (\Exception $ex) {
                $this->storeFlashMessage(
                    sprintf('No config found with id %d', $id),
                    FlashMessenger::NAMESPACE_ERROR
                );
                return $this->redirect()->toRoute('configAdmin', array('siteKey' => FhskSite::getKey()));
            }

            $cloneData = $config->getArrayCopy();
            $cloneData['id'] = null;
            $form->setData($cloneData);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $config = $this->getNewConfigEntity();
            $form->setInputFilter($config->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $config->exchangeArray($form->getData());
                $config = $this->getConfigTable()->saveConfig($config);
                $this->storeFlashMessage(
                    sprintf('Config %d (%s) created', $config->id, $config->name),
                    FlashMessenger::NAMESPACE_SUCCESS
                );

                // Redirect to list of configs

                return $this->redirect()->toRoute('configAdmin', array('siteKey' => FhskSite::getKey()));
            }
        }

        $this->viewData = array(
            'form'       => $form,
            'formAction' => 'create'
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

            return $this->redirect()->toRoute('configAdmin', array(
                'action'  => 'create',
                'siteKey' => FhskSite::getKey(),
            ));
        }

        // Get the Config with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $config = $this->getconfigTable()->getConfig($id);
        }
        catch (\Exception $ex) {
            $this->storeFlashMessage(
                sprintf('No config found with id %d', $id),
                FlashMessenger::NAMESPACE_ERROR
            );
            return $this->redirect()->toRoute('configAdmin', array(
                'siteKey' => FhskSite::getKey(),
            ));
        }

        $form = $this->getConfigForm();
        $form->bind($config);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($config->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getConfigTable()->saveConfig($config);
                $this->storeFlashMessage(
                    sprintf('Config %d (%s) updated', $config->id, $config->name),
                    FlashMessenger::NAMESPACE_SUCCESS
                );

                // Redirect to list of configs

                return $this->redirect()->toRoute('configAdmin', array(
                    'siteKey' => FhskSite::getKey(),
                    'action'  => $this->params()->fromRoute('returnAction', ''),
                ));
            }
        }

        $this->viewData = array(
            'id'           => $id,
            'form'         => $form,
            'formAction'   => 'edit',
            'config'      => $config,
            'returnAction' => $this->params()->fromRoute('returnAction', ''),
        );
        $view = $this->generateViewModel('edit');

        return $view;
    }

    /**
     * Get the Config Table
     * @return ConfigTableInterface
     * @throws \Exception
     */
    protected function getConfigTable()
    {
        if (! $this->configTable) {
            $configTable = $this->getServiceLocator()
                ->get('Config\Model\ConfigTable');
            if (! $configTable instanceof ConfigTableInterface) {
                throw new \Exception(sprintf('Config table must be an instance of FhSiteKit\FhskConfig\Model\ConfigTableInterface, "%s" given.', get_class($configTable)));
            }
            $this->configTable = $configTable;
        }

        return $this->configTable;
    }

    /**
     * Get the config form
     * @return ConfigForm
     * @throws \Exception
     */
    protected function getConfigForm()
    {
        $form = $this->getServiceLocator()
            ->get('FormElementManager')
            ->get('Config\Form\ConfigForm');
        if (! $form instanceof ConfigForm) {
            throw new \Exception(sprintf('Config form must be an instance of FhSiteKit\FhskConfig\Form\ConfigForm, "%s" given.', get_class($form)));
        }

        return $form;
    }

    /**
     * Get a new Config entity
     * @return Config
     * @throws \Exception
     */
    protected function getNewConfigEntity()
    {
        $config = $this->getServiceLocator()
            ->get('Config\Model\ConfigEntity');
        if (! $config instanceof Config) {
            throw new \Exception(sprintf('New Config entity must be an instance of FhSiteKit\FhskConfig\Model\Config, "%s" given.', get_class($config)));
        }

        return $config;
    }
}
