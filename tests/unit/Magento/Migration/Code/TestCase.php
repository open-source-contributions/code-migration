<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Migration\Code;

use Magento\Migration\Code\Processor\CallArgumentCollection;
use Magento\Migration\Code\Processor\CallArgumentCollectionFactory;
use Magento\Migration\Code\Processor\Mage\MageFunction\Argument;
use Magento\Migration\Code\Processor\Mage\MageFunction\ArgumentFactory;
use Magento\Migration\Code\Processor\TokenArgument;
use Magento\Migration\Code\Processor\TokenArgumentCollection;
use Magento\Migration\Code\Processor\TokenArgumentCollectionFactory;
use Magento\Migration\Code\Processor\TokenArgumentFactory;
use Magento\Migration\Code\Processor\TokenHelper;
use Magento\Migration\Logger\Logger;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;

class TestCase extends PHPUnitTestCase
{
    /**
     * @param Logger $loggerMock
     * @return TokenHelper
     */
    public function setupTokenHelper(Logger $loggerMock): TokenHelper
    {
        /** @var ArgumentFactory|PHPUnit_Framework_MockObject_MockObject $argumentFactoryMock */
        $argumentFactoryMock = $this->getMockBuilder(
            ArgumentFactory::class
        )->setMethods(['create'])
            ->getMock();
        $argumentFactoryMock->expects($this->any())
            ->method('create')
            ->willReturnCallback(
                function () {
                    return new Argument();
                }
            );

        /** @var TokenArgumentFactory|PHPUnit_Framework_MockObject_MockObject $tokenFactoryMock */
        $tokenFactoryMock = $this->getMockBuilder(
            TokenArgumentFactory::class
        )->setMethods(['create'])
            ->getMock();
        $tokenFactoryMock->expects($this->any())
            ->method('create')
            ->willReturnCallback(
                function () {
                    return new TokenArgument();
                }
            );

        /** @var TokenArgumentCollectionFactory|PHPUnit_Framework_MockObject_MockObject $tokenCollectionFactoryMock */
        $tokenCollectionFactoryMock = $this->getMockBuilder(
            TokenArgumentCollectionFactory::class
        )->setMethods(['create'])
            ->getMock();
        $tokenCollectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturnCallback(
                function () {
                    return new TokenArgumentCollection();
                }
            );

        /** @var CallArgumentCollectionFactory|PHPUnit_Framework_MockObject_MockObject $callCollectionFactoryMock */
        $callCollectionFactoryMock = $this->getMockBuilder(
            CallArgumentCollectionFactory::class
        )->setMethods(['create'])
            ->getMock();
        $callCollectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturnCallback(
                function () {
                    return new CallArgumentCollection();
                }
            );

        $tokenHelper = new TokenHelper(
            $loggerMock,
            $argumentFactoryMock,
            $tokenFactoryMock,
            $tokenCollectionFactoryMock,
            $callCollectionFactoryMock
        );

        return $tokenHelper;
    }
}
