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
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Instance\Controller\Admin' => 'Ndg\NdgNetwork\NdgInstance\Controller\AdminController'
        ),
    ),

    'router' => array(
        'routes' => array(
            'instanceAdmin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/:siteKey/admin/instance[/][:action][/:id][/:returnAction]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Instance\Controller\Admin',
                        'action'     => 'list',
                    ),
                ),
            ),
        ),
    ),

);