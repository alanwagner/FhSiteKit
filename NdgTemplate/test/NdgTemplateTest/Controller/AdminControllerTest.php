<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgTemplateTest\Controller;

use NdgPattern\Model\Pattern;
use NdgTemplate\Controller\AdminController;
use NdgTemplate\Model\Template;
use NdgTemplateTest\Bootstrap;
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
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $templateTableMock->expects($this->once())
            ->method('fetchDataWithPatternByIsArchived')
            ->with(0)
            ->will($this->returnValue(array()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/template';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testListArchivedActionCanBeAccessed()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $templateTableMock->expects($this->once())
            ->method('fetchDataWithPatternByIsArchived')
            ->with(1)
            ->will($this->returnValue(array()));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/template/list-archived';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testAddActionCanBeAccessed()
    {
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue(array($pattern)));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/template/add';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $template = $this->getTemplateWithData();
        $templateTableMock->expects($this->once())
            ->method('saveTemplate')
            ->will($this->returnValue($template));

        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue(array($pattern)));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getTemplateDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/template/add';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/template/');
        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testAddActionStaysOnPageAfterInvalidPost()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $templateTableMock->expects($this->never())
            ->method('saveTemplate');

        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue(array($pattern)));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getTemplateDataArray();
        $postData['name'] = '';
        $_SERVER['REQUEST_URI'] = '/mock/admin/template/add';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testAddActionRedirectsWhenCloningBadId()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $templateTableMock->expects($this->once())
            ->method('getTemplate')
            ->with(421)
            ->will($this->throwException(new \Exception()));

        $templateTableMock->expects($this->never())
            ->method('saveTemplate');

        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue(array($pattern)));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getTemplateDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/template/add/421';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/template/');
        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testEditActionCanBeAccessed()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $template = $this->getTemplateWithData();
        $templateTableMock->expects($this->once())
            ->method('getTemplate')
            ->with(420)
            ->will($this->returnValue($template));

        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue(array($pattern)));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/template/edit/420';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testEditActionRedirectsAfterValidPost()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $template = $this->getTemplateWithData();
        $templateTableMock->expects($this->once())
            ->method('getTemplate')
            ->with(420)
            ->will($this->returnValue($template));

        $templateTableMock->expects($this->once())
            ->method('saveTemplate')
            ->will($this->returnValue(null));

        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue(array($pattern)));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getTemplateDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/template/edit/420';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/template/');
        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testEditActionStaysOnPageAfterInvalidPost()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $template = $this->getTemplateWithData();
        $templateTableMock->expects($this->once())
            ->method('getTemplate')
            ->with(420)
            ->will($this->returnValue($template));

        $templateTableMock->expects($this->never())
            ->method('saveTemplate');

        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $pattern = $this->getPatternWithData();
        $patternTableMock->expects($this->once())
            ->method('fetchByIsArchived')
            ->with(0)
            ->will($this->returnValue(array($pattern)));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);
        $serviceManager->setService('Pattern\Model\PatternTable', $patternTableMock);

        $postData = $this->getTemplateDataArray();
        $postData['name'] = '';
        $_SERVER['REQUEST_URI'] = '/mock/admin/template/edit/420';
        $this->dispatch($_SERVER['REQUEST_URI'], 'POST', $postData);

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testEditActionRedirectsWhenBadId()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $templateTableMock->expects($this->once())
            ->method('getTemplate')
            ->with(421)
            ->will($this->throwException(new \Exception()));

        $templateTableMock->expects($this->never())
        ->method('saveTemplate');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);

        $postData = $this->getTemplateDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/template/edit/421';
        $this->dispatch($_SERVER['REQUEST_URI']);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/template/');
        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testEditActionRedirectsToAddFormWhenNoId()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $templateTableMock->expects($this->never())
            ->method('getTemplate');

        $templateTableMock->expects($this->never())
        ->method('saveTemplate');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);

        $postData = $this->getTemplateDataArray();
        $_SERVER['REQUEST_URI'] = '/mock/admin/template/edit';
        $this->dispatch($_SERVER['REQUEST_URI']);

        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/template/add');
        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testToggleArchivedActionCanBeAccessed()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $template = $this->getTemplateWithData();
        $templateTableMock->expects($this->once())
            ->method('getTemplate')
            ->with(420)
            ->will($this->returnValue($template));

        $data = $this->getTemplateDataArray();
        $data['is_archived'] = 0;
        $template->exchangeArray($data);
        $templateTableMock->expects($this->once())
            ->method('saveTemplate')
            ->with($template);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/template/toggle-archived/420/list-archived';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/template/list-archived');

        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testRedirectWhenTogglingWithBadId()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $templateTableMock->expects($this->once())
            ->method('getTemplate')
            ->with(421)
            ->will($this->throwException(new \Exception()));

        $templateTableMock->expects($this->never())
            ->method('saveTemplate');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/template/toggle-archived/421/list-archived';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/template/');

        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    public function testRedirectWhenTogglingWithNoId()
    {
        $templateTableMock = $this->getMockBuilder('NdgTemplate\Model\TemplateTable')
            ->disableOriginalConstructor()
            ->getMock();

        $templateTableMock->expects($this->never())
            ->method('getTemplate');

        $templateTableMock->expects($this->never())
            ->method('saveTemplate');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Template\Model\TemplateTable', $templateTableMock);

        $_SERVER['REQUEST_URI'] = '/mock/admin/template/toggle-archived';
        $this->dispatch($_SERVER['REQUEST_URI']);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/mock/admin/template/');

        $this->assertModuleName('NdgTemplate');
        $this->assertControllerName('Template\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('templateAdmin');
    }

    /**
     * Get Template entity initialized with standard data
     * @return NdgTemplate\Model\Template
     */
    protected function getTemplateWithData()
    {
        $template = new Template();
        $data  = $this->getTemplateDataArray();
        $template->exchangeArray($data);

        return $template;
    }

    /**
     * Get standard template data as array
     * @return array
     */
    protected function getTemplateDataArray()
    {
        return array(
            'id'            => 420,
            'pattern_id'    => 429,
            'name'          => 'template name',
            'description'   => 'N=3, Z=2',
            'instance_name' => '4.## Cond 1 #pattern',
            'serial'        => 19,
            'is_archived'   => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        );
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
            'is_archived' => 0,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        );
    }
}
