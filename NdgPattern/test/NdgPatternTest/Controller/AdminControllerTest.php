<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgPatternTest\Controller;

use NdgPattern\Controller\AdminController;
use NdgPattern\Model\Pattern;
use NdgPatternTest\Bootstrap;
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

    public function testListActionCanBeAccessed()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue(array()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testListArchivedActionCanBeAccessed()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(1)
            ->will($this->returnValue(array()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/list-archived';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testAddActionCanBeAccessed()
    {
        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/add';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('savePattern')
            ->will($this->returnValue($pattern));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getPatternDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/add';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/pattern/');
        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testAddActionStaysOnPageAfterInvalidPost()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->never())
            ->method('savePattern');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getPatternDataArray();
        $postData['name'] = '';
        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/add';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testAddActionRedirectsWhenCloningBadId()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->once())
            ->method('getPattern')
            ->with(421)
            ->will($this->throwException(new \Exception()));

        $patternTableMock->expects($this->never())
            ->method('savePattern');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getPatternDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/add/421';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/pattern/');
        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testEditActionCanBeAccessed()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('getPattern')
            ->with(420)
            ->will($this->returnValue($pattern));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/edit/420';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testEditActionRedirectsAfterValidPost()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('getPattern')
            ->with(420)
            ->will($this->returnValue($pattern));

        $patternTableMock->expects($this->once())
            ->method('savePattern')
            ->will($this->returnValue(null));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getPatternDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/edit/420';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/pattern/');
        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testEditActionStaysOnPageAfterInvalidPost()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('getPattern')
            ->with(420)
            ->will($this->returnValue($pattern));

        $patternTableMock->expects($this->never())
            ->method('savePattern');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getPatternDataArray();
        $postData['name'] = '';
        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/edit/420';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testEditActionRedirectsWhenBadId()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->once())
            ->method('getPattern')
            ->with(421)
            ->will($this->throwException(new \Exception()));

        $patternTableMock->expects($this->never())
        ->method('savePattern');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getPatternDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/edit/421';
        $this->dispatch($_SERVER['REQUEST_URI']);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/pattern/');
        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testEditActionRedirectsToAddFormWhenNoId()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->never())
            ->method('getPattern');

        $patternTableMock->expects($this->never())
        ->method('savePattern');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getPatternDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/edit';
        $this->dispatch($_SERVER['REQUEST_URI']);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/pattern/add');
        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testToggleArchivedActionCanBeAccessed()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('getPattern')
            ->with(420)
            ->will($this->returnValue($pattern));

        $data = $this->getPatternDataArray();
        $data['is_archived'] = 0;
        $pattern->exchangeArray($data);
        $patternTableMock->expects($this->once())
            ->method('savePattern')
            ->with($pattern);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/toggle-archived/420/list-archived';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/pattern/list-archived');

        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testRedirectWhenTogglingWithBadId()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->once())
            ->method('getPattern')
            ->with(421)
            ->will($this->throwException(new \Exception()));

        $patternTableMock->expects($this->never())
            ->method('savePattern');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/toggle-archived/421/list-archived';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/pattern/');

        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    public function testRedirectWhenTogglingWithNoId()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->never())
            ->method('getPattern');

        $patternTableMock->expects($this->never())
            ->method('savePattern');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/pattern/toggle-archived';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/pattern/');

        $this->assertModuleName('NdgPattern');
        $this->assertControllerName('Pattern\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('patternAdmin');
    }

    /**
     * Get Pattern entity initialized with standard data
     * @return NdgPattern\Model\Pattern
     */
    protected function getPatternWithData()
    {
        $pattern = new Pattern();
        $data  = $this->getPatternDataArray();
        $pattern->exchangeArray($data);

        return $pattern;
    }

    /**
     * Get standard pattern data as array
     * @return array
     */
    protected function getPatternDataArray()
    {
        return array(
            'id'          => 420,
            'name'        => 'pattern name',
            'content'     => "1 2 3\n2 1 3\n3 1 2",
            'description' => 'N=3, Z=2',
            'is_archived' => 1,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
    }
}
