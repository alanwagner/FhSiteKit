<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgTemplate\Model;

use FhSiteKit\FhskCore\FhskEntity\Model\EntityTableInterface;

/**
 * Template table interface
 */
interface TemplateTableInterface extends EntityTableInterface
{
    /**
     * Fetch all templates
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll();

    /**
     * Fetch only active or archived templates
     * @param int $isArchived  0 or 1
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchByIsArchived($isArchived);

    /**
     * Fetch RowData only on active or archived patterns, with pattern data
     * @param int $isArchived  0 or 1
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchDataWithPatternByIsArchived($isArchived);

    /**
     * Get a single template by id
     * @param int $id
     * @throws \Exception
     * @return Template
     */
    public function getTemplate($id);

    /**
     * Prepare and save template data
     *
     * Could be creating a new template or updating an existing one
     *
     * @param Template $template
     * @throws \Exception
     */
    public function saveTemplate(Template $template);

    /**
     * Delete a template
     * @param int $id
     */
    public function deleteTemplate($id);
}
