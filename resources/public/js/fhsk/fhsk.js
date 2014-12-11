/**
 * Farther Horizon Site Kit
 *
 * @link      http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2014 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

$( document ).ready(function() {
	$('tr.fhsk-hover').hover(
		function() {
			$('tr.fhsk-hover').removeClass('fhsk-hover-highlight');
			$(this).addClass('fhsk-hover-highlight');
			if ($(this).hasClass('fhsk-hover-too')) {
				$(this).prev().addClass('fhsk-hover-highlight');
			} else if ($(this).next().hasClass('fhsk-hover-too')) {
				$(this).next().addClass('fhsk-hover-highlight');
			}
		},
		function() {
			$('tr.fhsk-hover').removeClass('fhsk-hover-highlight');
		}
	);
});
