<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FHSK for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskSite\Core;

class Site
{
    /**
     * Site name
     * @var string
     */
    const SITE_NAME = 'Fhsk';

    /**
     * Get site key from REQUEST_URI
     *
     * @return string
     */
    public static function getKey()
    {
        return substr($_SERVER['REQUEST_URI'], 1, strpos($_SERVER['REQUEST_URI'], '/', 1) - 1);
    }

    /**
     * Get site name
     * @return string
     */
    public function getName()
    {
        return static::SITE_NAME;
    }
}