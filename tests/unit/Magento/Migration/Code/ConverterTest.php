<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Migration\Code;

use Magento\Migration\Logger\Logger;

class ConverterTest extends TestCase
{
    /**
     * @var \Magento\Migration\Code\Converter
     */
    protected $obj;

    /**
     * @var \Magento\Migration\Code\ProcessorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $processorMock;

    /**
     * @var \Magento\Migration\Code\SplitterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $splitterMock;
    /**
     * @var \Magento\Migration\Logger\Logger|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $loggerMock;

    protected function setUp(): void
    {
        $this->processorMock = $this->getMockBuilder(ProcessorInterface::class)
            ->getMockForAbstractClass();
        $this->splitterMock = $this->getMockBuilder(SplitterInterface::class)
            ->getMockForAbstractClass();
        $this->loggerMock = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()->getMock();

        $tokenHelper = $this->setupTokenHelper($this->loggerMock);

        $this->obj = new Converter(
            [$this->processorMock],
            [$this->splitterMock],
            $tokenHelper,
            $this->loggerMock
        );
    }

    public function testConvert(): void
    {
        $content = file_get_contents(__DIR__ . '/_files/test.php');

        $this->processorMock->expects($this->once())
            ->method('process')
            ->willReturnArgument(0);

        $converted = $this->obj->convert($content);

        $this->assertEquals($content, $converted);
    }
}
