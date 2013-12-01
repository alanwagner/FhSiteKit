<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace MockSite;

use FhSiteKit\FhskCore\FhskSite\Site as FhskSite;

/**
 * Mock site core Site properties and methods
 */
class Site extends FhskSite
{
    /**
     * Site name
     * @staticvar string
     * @see FhSiteKit\FhskCore\FhskSite\Site::$siteName
     */
    public static $siteName = 'MockName';
}
