<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore;

/**
 * Base class for core Site properties and methods
 */
class Site
{
    /**
     * Site name
     * @staticvar string
     */
    public static $siteName = 'FhSiteKit';

    /**
     * Get site key from FHSK_SITE_KEY or from REQUEST_URI
     * @return string
     */
    public static function getKey()
    {
        $siteKey = '';

        if (isset($_SERVER['FHSK_SITE_KEY'])) {
            $siteKey = $_SERVER['FHSK_SITE_KEY'];
        } else {
            $pathParts = explode('/', substr($_SERVER['REQUEST_URI'], 1));
            $siteKey = $pathParts[0];
            if (strstr($siteKey, '?')) {
                $siteKey = substr($siteKey, 0, strpos($siteKey, '?'));
            }
        }

        return $siteKey;
    }

    /**
     * Get site name
     * @return string
     */
    public function getName()
    {
        return static::$siteName;
    }
}
