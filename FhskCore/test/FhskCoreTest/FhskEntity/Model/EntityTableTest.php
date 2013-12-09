<?php
/**
 * Farther Horizon Site Kit
 *
 * @link       http://github.com/alanwagner/FhSiteKit for the canonical source repository
 * @copyright Copyright (c) 2013 Farther Horizon SARL (http://www.fartherhorizon.com)
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPLv3 License
 * @author    Alan Wagner (mail@alanwagner.org)
 */

namespace FhskCoreTest\FhskEntity\Model;

use FhSiteKit\FhskCore\Model\EntityTable;
use PHPUnit_Framework_TestCase;

/**
 * Tests on the EntityTable class
 */
class EntityTableTest extends PHPUnit_Framework_TestCase
{

    public function testGetTableGateway()
    {
        $mockTableGateway = $this->getMock(
            'Zend\Db\TableGateway\TableGateway',
            array('select'),
            array(),
            '',
            false
        );

        $entityTable = new EntityTable($mockTableGateway);

        $this->assertSame($mockTableGateway, $entityTable->getTableGateway());
    }
}
