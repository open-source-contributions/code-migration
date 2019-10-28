<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Migration\Code;

use Magento\Migration\Logger\Logger;
use Magento\Migration\Mapping\ClassMapping;
use PHPUnit\Framework\TestCase;

class ClassMappingTest extends TestCase
{
    /**
     * @var \Magento\Migration\Mapping\ClassMapping
     */
    protected $obj;

    /**
     * @var \Magento\Migration\Logger\Logger|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $loggerMock;

    protected function setUp(): void
    {
        $this->loggerMock = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()->getMock();

        $this->obj = new ClassMapping(
            $this->loggerMock
        );
    }

    public function testMapM1Class(): void
    {
        $this->assertEquals("\\Magento\\Backend\\Helper\\Data", $this->obj->mapM1Class('Mage_Admin_Helper_Data'));
        $this->assertEquals(
            "\\Magento\\Catalog\\Model\\Product\\Media\\ConfigInterface",
            $this->obj->mapM1Class('Mage_Media_Model_Image_Config_Interface')
        );
        $this->assertEquals(
            "obsolete",
            $this->obj->mapM1Class('Mage')
        );
    }
}
