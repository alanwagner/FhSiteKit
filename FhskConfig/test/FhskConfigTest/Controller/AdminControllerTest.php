<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskConfigTest\Controller;

use FhSiteKit\FhskConfig\Controller\AdminController;
use FhSiteKit\FhskConfig\Model\Config;
use FhskConfigTest\Bootstrap;
use MockConfig\Service\Config as MockConfig;
use Zend\Db\ResultSet\ResultSet;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Tests on the Admin Controller
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

    /**
     * By using $configServiceMock we also test that getConfigArray() is called exactly once,
     *   by the Config Module's bootstrapped listener
     */
    public function testListActionCanBeAccessed()
    {
        $configServiceMock = $this->getConfigServiceMock();
        $configServiceMock->expects($this->once())
            ->method('getConfigArrayFormatted')
            ->with()
            ->will($this->returnValue($this->getServiceConfigArray()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('FhskConfig', $configServiceMock);

        $_SERVER['REQUEST_URI'] = '/fhsk/admin/config';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertEquals('FhSiteKit\FhskConfig\Controller\AdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Config\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('configAdmin');
    }

    public function testConfigureActionCanBeAccessed()
    {
        $mockConfig = new MockConfig();
        $mockConfig->setConfig($this->getSiteConfigArray());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('FhskConfig', $mockConfig);

        $_SERVER['REQUEST_URI'] = '/fhsk/admin/config/configure/Foo';
        $this->dispatch($_SERVER['REQUEST_URI']);

        $this->assertResponseStatusCode(200);
        $this->assertEquals('FhSiteKit\FhskConfig\Controller\AdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Config\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('configAdmin');
    }


    public function testConfigureActionRedirectsWhenNoConfigKey()
    {
        $mockConfig = new MockConfig();
        $mockConfig->setConfig($this->getSiteConfigArray());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('FhskConfig', $mockConfig);

        $_SERVER['REQUEST_URI'] = '/fhsk/admin/config/configure';
        $this->dispatch($_SERVER['REQUEST_URI']);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/fhsk/admin/config/');

        $this->assertEquals('FhSiteKit\FhskConfig\Controller\AdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Config\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('configAdmin');
    }


    public function testConfigureActionRedirectsWhenUnknownConfigKey()
    {
        $mockConfig = new MockConfig();
        $mockConfig->setConfig($this->getSiteConfigArray());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('FhskConfig', $mockConfig);

        $_SERVER['REQUEST_URI'] = '/fhsk/admin/config/configure/Zappa';
        $this->dispatch($_SERVER['REQUEST_URI']);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/fhsk/admin/config/');

        $this->assertEquals('FhSiteKit\FhskConfig\Controller\AdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Config\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('configAdmin');
    }

    public function testConfigureActionRedirectsAfterValidPost()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $postData = array(
            'id'           => 420,
            'config_key'   => 'Foo',
            'config_value' => 'oof',
        );

        $dbData = $this->getConfigDataArray();
        $dbData['config_value'] = 'oof';

        $mockConfig = new MockConfig();
        $mockConfig->setConfig($this->getSiteConfigArray());
        $serviceManager->setService('FhskConfig', $mockConfig);

        $mockConfigEntity = new Config();
        $mockConfigEntity->exchangeArray($dbData);
        $configTableMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Model\ConfigTable')
            ->disableOriginalConstructor()
            ->getMock();
        $configTableMock->expects($this->once())
            ->method('saveConfig')
            ->will($this->returnValue($mockConfigEntity));
        $serviceManager->setService('Config\Model\ConfigTable', $configTableMock);

        $_SERVER['REQUEST_URI'] = '/fhsk/admin/config/configure/Foo';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/fhsk/admin/config/');
        $this->assertEquals('FhSiteKit\FhskConfig\Controller\AdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Config\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('configAdmin');
    }

    public function testCreateActionStaysOnPageAfterInvalidPost()
    {
        $mockConfig = new MockConfig();
        $mockConfig->setConfig($this->getSiteConfigArray());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('FhskConfig', $mockConfig);

        $postData = array(
            'id'           => 420,
            'config_key'   => '',
            'config_value' => 'oof',
        );
        $_SERVER['REQUEST_URI'] = '/fhsk/admin/config/configure/Foo';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(200);
        $this->assertEquals('FhSiteKit\FhskConfig\Controller\AdminController', $this->getControllerFullClassName());
        $this->assertControllerName('Config\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('configAdmin');
    }

    /**
     * Get Config entity initialized with standard data
     * @return FhSiteKit\FhskConfig\Model\Config
     */
    protected function getConfigWithData()
    {
        $config = new Config();
        $data  = $this->getConfigDataArray();
        $config->exchangeArray($data);

        return $config;
    }

    /**
     * Get standard config data as array
     * @return array
     */
    protected function getConfigDataArray()
    {
        return array(
            'id'          => 420,
            'config_key' => 'Foo',
            'config_value' => 'bar',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
    }

    /**
     * Get standard config data as array
     * @return array
     */
    protected function getSiteConfigArray()
    {
        return array(
            'Foo' => 'bar',
            'Bar' => null,
        );
    }

    /**
     * Get mock result of service getConfigArray()
     * @return array
     */
    protected function getServiceConfigArray()
    {
        $mockConfig = new MockConfig();
        $mockConfig->setConfig($this->getSiteConfigArray());

        return $mockConfig->getConfigArray();
    }

    /**
     * Get mock result of service getConfigArrayFormatted()
     * @return array
     */
    protected function getServiceConfigArrayFormatted()
    {
        $mockConfig = new MockConfig();
        $mockConfig->setConfig($this->getConfigArrayFormatted());

        return $mockConfig->getConfigArray();
    }

    protected function getConfigServiceMock()
    {
        $configServiceMock = $this->getMockBuilder('FhSiteKit\FhskConfig\Service\Config')
            ->disableOriginalConstructor()
            ->getMock();
        //  checks call from listener
        $configServiceMock->expects($this->once())
            ->method('getConfigArray')
            ->with()
            ->will($this->returnValue($this->getServiceConfigArray()));

        return $configServiceMock;
    }
}
