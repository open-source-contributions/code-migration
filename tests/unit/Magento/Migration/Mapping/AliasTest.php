<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Migration\Code;

use Magento\Migration\Mapping\Context;
use Magento\Migration\Logger\Logger;
use Magento\Migration\Mapping\Alias;
use PHPUnit\Framework\TestCase;

class AliasTest extends TestCase
{
    /**
     * @var \Magento\Migration\Mapping\Alias
     */
    protected $obj;

    /**
     * @var \Magento\Migration\Mapping\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Migration\Logger\Logger|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $loggerMock;

    protected function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(
            Context::class
        )->disableOriginalConstructor()->getMock();

        $this->loggerMock = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()->getMock();

        $this->obj = new Alias(
            $this->loggerMock,
            $this->contextMock
        );
    }

    public function testMapAliasNoM1BaseDir(): void
    {
        $this->contextMock->expects($this->once())
            ->method('getM1BaseDir')
            ->willReturn(null);

        $this->assertEquals('Mage_Tax_Helper', $this->obj->mapAlias('tax', 'helper'));
    }

    public function testMapAlias(): void
    {
        $baseDir = __DIR__ . '/_files/alias_test';
        $this->contextMock->expects($this->exactly(3))
            ->method('getM1BaseDir')
            ->willReturn($baseDir);

        $this->assertEquals('Mage_Tax_Helper', $this->obj->mapAlias('tax', 'helper'));
        $this->assertEquals('Mdev_Tax_Helper', $this->obj->mapAlias('mdev_tax', 'helper'));
        $this->assertEquals('Mcompany_Tax_Helper', $this->obj->mapAlias('mcompany_tax', 'helper'));

        $this->assertEquals('Mage_Tax_Model', $this->obj->mapAlias('tax', 'model'));
        $this->assertEquals('Mdev_Tax_Model', $this->obj->mapAlias('mdev_tax', 'model'));
        $this->assertEquals('Mcompany_Tax_Model', $this->obj->mapAlias('mcompany_tax', 'model'));

        $this->assertEquals('Mage_Tax_Block', $this->obj->mapAlias('tax', 'block'));
        $this->assertEquals('Mdev_Tax_Block', $this->obj->mapAlias('mdev_tax', 'block'));
        $this->assertEquals('Mcompany_Tax_Block', $this->obj->mapAlias('mcompany_tax', 'block'));
    }
}
