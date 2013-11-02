<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace Ndg\NdgNetwork\Service;

use Ndg\NdgNetwork\NdgInstance\Model\InstanceTableInterface;
use Ndg\NdgPattern\Model\PatternTableInterface;
use Ndg\NdgTemplate\Model\TemplateTableInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * NdgNetworkManager service
 */
class NetworkManager
{
    /**
     * The application service locator
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * The template table gateway
     * @var TemplateTableInterface
     */
    protected $templateTable;

    /**
     * The pattern table gateway
     * @var PatternTableInterface
     */
    protected $patternTable;

    /**
     * The instance table gateway
     * @var InstanceTableInterface
     */
    protected $instanceTable;

    /**
     * The template entity
     * @var Ndg\NdgTemplate\Model\Template;
     */
    protected $template;

    /**
     * The pattern entity
     * @var Ndg\NdgPattern\Model\Pattern
     */
    protected $pattern;

    /**
     * The instance entity
     * @var Ndg\NdgNetwork\NdgInstance\Model\Instance
     */
    protected $instance;

    /**
     * Constructor
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Spawn an instance from a template id
     * @param int $templateId
     * @return Ndg\NdgNetwork\NdgInstance\Model\Instance
     */
    public function spawnInstanceFromTemplateId($templateId)
    {
        $this->template = $this->getTemplateTable()->getTemplate($templateId);
        $this->pattern  = $this->getPatternTable()->getPattern($this->template->pattern_id);

        $data = array(
            'name'         => $this->generateInstanceNameFromTemplate(),
            'pattern_name' => $this->pattern->name,
            'description'  => '',
            'status'       => '',
            'is_archived'  => 0,
        );

        $instance = $this->getInstanceEntity();
        $instance->exchangeArray($data);
        $this->instance = $this->getInstanceTable()->saveInstance($instance);

        $this->updateTemplateSerial();

        return $this->instance;
    }

    /**
     * Generate instance name from template instance_name
     * @return string
     */
    protected function generateInstanceNameFromTemplate()
    {
        $name   = $this->template->instance_name;

        $name = str_replace('#pattern', $this->pattern->name, $name);

        $serial = $this->getNextSerial();

        $name = str_replace('###', str_pad($serial, 3, '0', STR_PAD_LEFT), $name);
        $name = str_replace('##', str_pad($serial, 2, '0', STR_PAD_LEFT), $name);
        $name = str_replace('#', $serial, $name);

        return $name;
    }

    /**
     * Update template serial number in db
     */
    protected function updateTemplateSerial()
    {
        $this->template->exchangeArray(array('serial' => $this->getNextSerial()));
        $this->getTemplateTable()->saveTemplate($this->template);
    }

    /**
     * Apply the rule for generating the next template serial number to the current template
     * @return string
     */
    protected function getNextSerial()
    {
        $serial = $this->template->serial;
        $serial ++;

        return $serial;
    }

    /**
     * Get the Template Table
     * @return TemplateTableInterface
     */
    protected function getTemplateTable()
    {
        if (! $this->templateTable) {
            $templateTable = $this->serviceLocator->get('Template\Model\TemplateTable');
            if (! $templateTable instanceof TemplateTableInterface) {
                throw new \Exception(sprintf('Template table must be an instance of Ndg\NdgTemplate\Model\TemplateTableInterface, "%s" given.', get_class($templateTable)));
            }
            $this->templateTable = $templateTable;
        }

        return $this->templateTable;
    }

    /**
     * Get the Pattern Table
     * @return PatternTableInterface
     */
    protected function getPatternTable()
    {
        if (! $this->patternTable) {
            $patternTable = $this->serviceLocator->get('Pattern\Model\PatternTable');
            if (! $patternTable instanceof PatternTableInterface) {
                throw new \Exception(sprintf('Pattern table must be an instance of Ndg\NdgPattern\Model\PatternTableInterface, "%s" given.', get_class($patternTable)));
            }
            $this->patternTable = $patternTable;
        }

        return $this->patternTable;
    }

    /**
     * Get the Instance Table
     * @return InstanceTableInterface
     */
    protected function getInstanceTable()
    {
        if (! $this->instanceTable) {
            $instanceTable = $this->serviceLocator->get('Instance\Model\InstanceTable');
            if (! $instanceTable instanceof InstanceTableInterface) {
                throw new \Exception(sprintf('Instance table must be an instance of Ndg\NdgNetwork\NdgInstance\Model\InstanceTableInterface, "%s" given.', get_class($instanceTable)));
            }
            $this->instanceTable = $instanceTable;
        }

        return $this->instanceTable;
    }

    /**
     * Get an Instance Entity
     * @return mixed
     */
    protected function getInstanceEntity()
    {
        return $this->serviceLocator->get('Instance\Model\InstanceEntity');
    }
}
