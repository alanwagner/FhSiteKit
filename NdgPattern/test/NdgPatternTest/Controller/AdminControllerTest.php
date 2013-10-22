<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace NdgPatternTest\Controller;

use NdgPattern\Model\Pattern;
use NdgPatternTest\Bootstrap;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Tests on the Admin Controller
 */
class PatternTest extends AbstractHttpControllerTestCase
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
        $patternTableMock = $this->getMockBuilder('NdgPattern\Model\PatternTable')
            ->disableOriginalConstructor()
            ->getMock();

        $patternTableMock->expects($this->once())
            ->method('fetchAll')
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
}