<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgNetwork\NdgInstance\Controller;

use FhSiteKit\FhskCore\FhskEntity\Controller\AdminController as FhskAdminController;
use FhSiteKit\FhskCore\FhskSite\Core\Site as FhskSite;
use Ndg\NdgNetwork\NdgInstance\Model\Instance;
use Ndg\NdgNetwork\NdgInstance\Model\InstanceTableInterface;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

/**
 * Instance admin controller
 */
class AdminController extends FhskAdminController
{
    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'instance';

    /**
     * The instance table
     * @var InstanceTableInterface
     */
    protected $instanceTable;

    /**
     * Handle a list page request
     *
     * This shows only active instances
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function viewAction()
    {

        $id = (int) $this->params()->fromRoute('id', 0);
        if (empty($id)) {
            //  Go to default page if no id
            return $this->redirect()->toRoute('instanceAdmin', array('siteKey' => FhskSite::getKey()));
        }

        // Get the Instance with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the default page.
        try {
            $instance = $this->getInstanceTable()->getInstance($id);
        }
        catch (\Exception $ex) {
            $this->storeFlashMessage(
                sprintf('No instance found with id %d', $id),
                FlashMessenger::NAMESPACE_ERROR
            );
            return $this->redirect()->toRoute('instanceAdmin', array('siteKey' => FhskSite::getKey()));
        }

        $this->viewData = array(
            'instance' => $instance,
        );
        $view = $this->generateViewModel('view');

        return $view;
    }

    /**
     * Handle a list page request
     *
     * This shows only active instances
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction()
    {
        $this->viewData = array(
            'instances' => $this->getInstanceTable()->fetchByIsArchived(0),
        );
        $view = $this->generateViewModel('list');

        return $view;
    }

    /**
     * Handle a request for a list of archived instances
     * @return \Zend\View\Model\ViewModel
     */
    public function listArchivedAction()
    {
        $this->viewData = array(
            'instances'      => $this->getInstanceTable()->fetchByIsArchived(1),
            'isArchiveList' => true,
        );
        $view = $this->generateViewModel('list');

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
            // Get the Instance with the specified id.  An exception is thrown
            // if it cannot be found, in which case go to the index page.
            try {
                $instance = $this->getInstanceTable()->getInstance($id);
            }
            catch (\Exception $ex) {
                $this->storeFlashMessage(
                    sprintf('No instance found with id %d', $id),
                    FlashMessenger::NAMESPACE_ERROR
                );
                return $this->redirect()->toRoute('instanceAdmin', array('siteKey' => FhskSite::getKey()));
            }

            $instance->is_archived = empty($instance->is_archived) ? 1 : 0;
            $this->getInstanceTable()->saveInstance($instance);
            $this->storeFlashMessage(
                sprintf('Instance %d (%s) %s', $instance->id, $instance->name, (empty($instance->is_archived) ? 'unarchived' : 'archived')),
                FlashMessenger::NAMESPACE_SUCCESS
            );
        }

        return $this->redirect()->toRoute('instanceAdmin', array('siteKey' => FhskSite::getKey(), 'action' => $this->params()->fromRoute('returnAction', '')));
    }

    /**
     * Get the Instance Table
     * @return InstanceTableInterface
     * @throws \Exception
     */
    protected function getInstanceTable()
    {
        if (! $this->instanceTable) {
            $instanceTable = $this->getServiceLocator()
                ->get('Instance\Model\InstanceTable');
            if (! $instanceTable instanceof InstanceTableInterface) {
                throw new \Exception(sprintf('Instance table must be an instance of Ndg\NdgNetwork\NdgInstance\Model\InstanceTableInterface, "%s" given.', get_class($instanceTable)));
            }
            $this->instanceTable = $instanceTable;
        }

        return $this->instanceTable;
    }
}
