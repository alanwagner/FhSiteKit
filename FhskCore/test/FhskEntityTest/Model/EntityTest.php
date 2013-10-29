<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskEntityTest\Model;

use FhSiteKit\FhskCore\FhskEntity\Model\Entity;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the FhSiteKit\FhskCore\FhskEntity\Model\Entity class
 */
class EntityTest extends PHPUnit_Framework_TestCase
{
    public function testEntityInitialState()
    {
        $entity = new Entity();

        $this->assertNull($entity->id);
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $entity = $this->getEntityWithData();
        $data  = $this->getDataArray();

        $this->assertSame($data['id'], $entity->id);
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $entity = $this->getEntityWithData();
        $entity->exchangeArray(array());

        $this->assertNull($entity->id);
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $entity = $this->getEntityWithData();
        $data  = $this->getDataArray();
        $copyArray = $entity->getArrayCopy();

        $this->assertSame($data['id'], $copyArray['id']);
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $entity = new Entity();

        $inputFilter = $entity->getInputFilter();

        $this->assertSame(0, $inputFilter->count());
    }


    /**
     * Get Entity entity initialized with standard data
     * @return NdgEntity\Model\Entity
     */
    protected function getEntityWithData()
    {
        $entity = new Entity();
        $data  = $this->getDataArray();
        $entity->exchangeArray($data);

        return $entity;
    }

    /**
     * Get standard data as array
     * @return array
     */
    protected function getDataArray()
    {
        return array(
            'id'          => 420,
        );
    }
}
