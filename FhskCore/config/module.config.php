<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

return array(
    'view_helpers' => array(
        'invokables' => array(
            'site' => 'FhSiteKit\FhskCore\View\Helper\Site'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Entity\Controller\Admin' => 'FhSiteKit\FhskCore\FhskEntity\Controller\AdminController',
            'Site\Controller\Admin' => 'FhSiteKit\FhskCore\FhskSite\Controller\AdminController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'siteAdmin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/:siteKey/admin/site[/][:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Site\Controller\Admin',
                        'action'     => 'index',
                    ),
                ),
            ),
            'entityAdmin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/:siteKey/admin/entity[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Entity\Controller\Admin',
                        'action'     => 'list',
                    ),
                ),
            ),
        ),
    ),

);
