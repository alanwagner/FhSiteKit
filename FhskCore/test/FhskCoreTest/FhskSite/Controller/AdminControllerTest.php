<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskCoreTest\FhskSite\Controller;

use FhSiteKit\FhskCore\Model\Entity;
use FhskCoreTest\Bootstrap;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Tests on the base Admin Controller
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

    public function testIndexActionCanBeAccessed()
    {
        $_SERVER['REQUEST_URI'] = '/mock/admin/site';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertEquals('FhSiteKit\FhskCore\Controller\AdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Site\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('siteAdmin');
    }
}
