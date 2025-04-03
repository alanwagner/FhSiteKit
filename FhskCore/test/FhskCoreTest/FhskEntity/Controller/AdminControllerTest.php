<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskCoreTest\FhskEntity\Controller;

use FhskCoreTest\Bootstrap;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Tests on the Entity Admin Controller
 */
class AdminControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include Bootstrap::getTestDirPath() . '/resources/config/application.config.php'
        );
        parent::setUp();
    }

    public function testListActionCanBeAccessed()
    {
        $_SERVER['REQUEST_URI'] = '/mock/admin/entity';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertEquals('FhSiteKit\FhskCore\Controller\EntityAdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Entity\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('entityAdmin');
    }

    public function testCreateActionCanBeAccessed()
    {
        $_SERVER['REQUEST_URI'] = '/mock/admin/entity/create';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertEquals('FhSiteKit\FhskCore\Controller\EntityAdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Entity\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('entityAdmin');
    }
}
