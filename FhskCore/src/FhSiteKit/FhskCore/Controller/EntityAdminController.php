<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\Controller;

use FhSiteKit\FhskCore\Controller\AdminController as FhskAdminController;

/**
 * Base entity admin controller
 */
class EntityAdminController extends FhskAdminController
{
    /**
     * Template namespace
     * @var string
     */
    protected static $templateNamespace  = 'entity';

    public function csvAction()
    {
        $queryName = $this->params()->fromRoute('queryName', '');
        $queryLibrary = $this->getServiceLocator()->get('FhskQueryLibrary');
        $query = $queryLibrary->getQueryByName($queryName);

        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $statement = $adapter->query($query);

        $id = (int) $this->params()->fromRoute('id', 0);
        $resultSet = $statement->execute(array('id' => $id));

        $fz = fopen('/tmp/fhsk.csv', 'w');
        $needsHeaders = true;
        while ($row = $resultSet->current()) {
            if ($needsHeaders) {
                fputcsv($fz, array_keys($row));
                $needsHeaders = false;
            }
            fputcsv($fz, $row);
        }
        fclose($fz);

        $content = file_get_contents('/tmp/fhsk.csv');
        unlink('/tmp/fhsk.csv');

        $this->viewData = array(
            'csvContent' => $content,
        );
        $view = $this->generateViewModel('csv');
        $view->setTerminal(true);
        $filename = sprintf('%s_%d.csv', $queryName, $id);
        $this->getResponse()->getHeaders()
            ->addHeaderLine('Content-Type', 'text/csv')
            ->addHeaderLine('Content-Disposition', 'attachment;filename='.$filename);
        return $view;

    }

    /**
     * Handle a list page request
     * @return \Zend\View\Model\ViewModel
     */
    public function listAction() {
        $view = $this->generateViewModel('list');

        return $view;
    }

    /**
     * Handle an add form page request or post submission
     * @return \Zend\View\Model\ViewModel
     */
    public function addAction() {
        $view = $this->generateViewModel('create');

        return $view;
    }
}
