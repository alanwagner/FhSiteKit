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
            'Email\Controller\Admin' => 'FhSiteKit\FhskEmail\Controller\AdminController'
        ),
    ),

    'router' => array(
        'routes' => array(
            'emailAdmin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/:siteKey/admin/email[/][:action][/:emailKey]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Email\Controller\Admin',
                        'action'     => 'list',
                    ),
                ),
            ),
        ),
    ),

);
