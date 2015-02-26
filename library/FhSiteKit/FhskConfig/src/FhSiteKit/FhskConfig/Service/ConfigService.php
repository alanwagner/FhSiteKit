<?php
/**
 * Farther Horizon Site Kit
 *
 * @link      http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2014 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhSiteKit\FhskConfig\Service;

use Symfony\Component\Yaml\Yaml;
use Zend\XmlRpc\Value\ArrayValue;

/**
 * Fhsk ConfigService class
 */
class ConfigService
{
    /**
     * Internal array of Configs
     * @var array
     */
    protected $config = array();

    /**
     * Get config by key
     * @return mixed
     */
    public function get($key)
    {
        $return = null;
        if (empty($this->config)) {
            $contentYml = file_get_contents('./config/application.config.yml');
            $content = Yaml::parse($contentYml);
            $this->config = $content;
        }
        if (isset($this->config[$key])) {
            $return = $this->config[$key];
        }

        return $return;
    }

    /**
     * Get raw Yml from file
     * @return mixed
     */
    public function getRawYml($file)
    {
        $contentYml = file_get_contents('./config/'.$file);

        return $contentYml;
    }
}
