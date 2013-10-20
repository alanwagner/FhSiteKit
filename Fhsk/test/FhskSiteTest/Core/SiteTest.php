<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskSiteTest\Core;

use FhskSiteTest\Bootstrap;
use FhskSite\Core\Site;
use PHPUnit_Framework_TestCase;

class SiteTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGetKey()
    {
        $_SERVER['REQUEST_URI'] = '/mock/somecontroller';

        $siteKey = Site::getKey();
        $this->assertEquals('mock', $siteKey);
    }

    public function testGetName()
    {
        $site = new Site();
        $name = $site->getName();
        $this->assertEquals('Fhsk', $name);
    }
}