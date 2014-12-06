<?php
/**
 * Farther Horizon Site Kit
 *
 * @link      http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2014 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Site\Controller\Index' => 'FhSiteKit\FhskDemo\Site\Controller\IndexController'
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'fh-site-kit/index/index' => __DIR__ . '/../view/site/index/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'router' => array(
        'routes' => array(
            'home' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Site\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

);
