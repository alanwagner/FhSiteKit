<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskEmail\Controller;

use FhSiteKit\FhskCore\Controller\BaseController as FhskCoreBaseController;
use FhSiteKit\FhskEmail\Service\EmailManager;

/**
 * Base controller
 *
 * Provides system-wide functions for accessing email manager
 */
class BaseController extends FhskCoreBaseController
{
    /**
     * The email manager
     * @var EmailManager
     */
    protected $emailManager;

    /**
     * Get email manager service
     * @return EmailManager
     * @throws \Exception
     */
    protected function getEmailManager()
    {
        if (! $this->emailManager) {
            $emailManager = $this->getServiceLocator()
                ->get('Email\Service\EmailManager');
            if (! $emailManager instanceof EmailManager) {
                throw new \Exception(sprintf('Email manager must be an instance of FhSiteKit\FhskEmail\Service\EmailManager, "%s" given.', get_class($emailManager)));
            }
            $this->emailManager = $emailManager;
        }
        return $this->emailManager;
    }
}
