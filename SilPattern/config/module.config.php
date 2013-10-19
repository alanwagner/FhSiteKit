<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
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
            'Pattern\Controller\Admin' => 'SilPattern\Controller\AdminController'
        ),
    ),

    'router' => array(
        'routes' => array(
            'patternAdmin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/:site/admin/pattern[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Pattern\Controller\Admin',
                        'action'     => 'list',
                    ),
                ),
            ),
        ),
    ),

);
