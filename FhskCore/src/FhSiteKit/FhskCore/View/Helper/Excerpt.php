<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskCore\View\Helper;

use Laminas\View\Helper\AbstractHelper;

/**
 * Helper to provide string excerpt cut at word boundary
 */
class Excerpt extends AbstractHelper
{
    /**
     * Provide string excerpt to last word boundary within character limit
     * @param string  $str
     * @param int     $lim
     * @param boolean $dots
     * @return string
     */
    public function __invoke($str, $lim, $dots = false)
    {
        $str = trim($str);
        if (strlen($str) > $lim) {
            $str = substr($str, 0, strrpos(substr($str,0,$lim+1), ' '));
            if ($dots) {
                $str .= '...';
            }
        }

        return $str;
    }
}
