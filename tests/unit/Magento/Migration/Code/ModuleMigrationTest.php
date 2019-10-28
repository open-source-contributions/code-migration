<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Migration\Code;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Migration\Logger\Logger;
use Magento\Migration\Code\ModuleMigration\ModuleFileExtractorFactory;
use Magento\Migration\Code\ModuleMigration\ModuleFileCopierFactory;
use Magento\Migration\Utility\M1\ModuleEnablerConfigFactory;
use Magento\Migration\Code\ModuleMigration\ModuleFileCopier;
use Magento\Migration\Code\ModuleMigration\ModuleFileExtractor;
use Magento\Migration\Utility\M1\ModuleEnablerConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class ModuleMigrationTest
 */
class ModuleMigrationTest extends TestCase
{
    /**
     * @var \Magento\Migration\Code\ModuleMigration
     */
    protected $model;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $logger;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $moduleFileExtractorFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $moduleFileCopierFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $moduleEnablerConfigFactory;

    /**
     * @var string
     */
    protected $m2Path;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * Setup the test
     */
    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);

        $this->logger = $this->getMockBuilder(Logger::class)->disableOriginalConstructor()->getMock();

        $this->moduleFileExtractorFactory = $this->getMockBuilder(ModuleFileExtractorFactory::class)->setMethods(['create'])->getMock();

        $this->moduleFileCopierFactory = $this->getMockBuilder(ModuleFileCopierFactory::class)->setMethods(['create'])->getMock();

        $this->moduleEnablerConfigFactory = $this->getMockBuilder(ModuleEnablerConfigFactory::class)->setMethods(['create'])->getMock();

        $this->m2Path = '/path/to/m2';

        $this->model = $this->objectManager->getObject(
            ModuleMigration::class,
            [
                'logger' => $this->logger,
                'moduleFileExtractorFactory' => $this->moduleFileExtractorFactory,
                'moduleFileCopierFactory' => $this->moduleFileCopierFactory,
                'moduleEnablerConfigFactory' => $this->moduleEnablerConfigFactory,
                'm2Path' => $this->m2Path,
            ]
        );
    }

    /**
     * @dataProvider moveModuleFilesProvider
     * @param array $namespaces
     * @param string $codePool
     * test MoveModuleFiles
     */
    public function testMoveModuleFiles(array $namespaces, string $codePool): void
    {
        $arrayBlockFiles = ['Block' => [$this->m2Path . '/app/code/Vendor1/Module1/Block/file1']];
        $arrayFrontendXMLFiles = ['frontendXml' => [$this->m2Path . '/app/code/Vendor1/Module1/Block/file2']];

        $moduleFileCopier = $this->getMockBuilder(ModuleFileCopier::class)->disableOriginalConstructor()->getMock();

        $moduleFileExtractor = $this->getMockBuilder(ModuleFileExtractor::class)->disableOriginalConstructor()->getMock();

        $moduleEnablerConfigFactory = $this->getMockBuilder(ModuleEnablerConfig::class)->disableOriginalConstructor()->getMock();

        $this->moduleFileExtractorFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($moduleFileExtractor);

        $this->moduleFileCopierFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($moduleFileCopier);

        $this->moduleEnablerConfigFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($moduleEnablerConfigFactory);

        $moduleEnablerConfigFactory->expects($this->atLeastOnce())
                    ->method('isModuleEnabled')
                    ->willReturn(true);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getModuleName')
            ->willReturn('Vendor1_Module1');

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getViewLayoutXmlFromFiles')
            ->willReturn($arrayFrontendXMLFiles);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getTranslationsFromConfig')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getTranslationsFromFiles')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getModelsFromFiles')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getHelpersFromFiles')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getBlockFromFiles')
            ->willReturn($arrayBlockFiles);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getControllersFromFiles')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getEtcFromFiles')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getViewLayoutXmlFromFiles')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getViewTemplatesFromFiles')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getSkinJsFromFiles')
            ->willReturn([]);

        $moduleFileExtractor->expects($this->atLeastOnce())
            ->method('getViewFromConfig')
            ->willReturn([]);


        $moduleFileCopier->expects($this->atLeastOnce())
            ->method('createM2ModuleFolder')
            ->willReturn(true);

        $files = current($arrayBlockFiles);
        $key = key($arrayBlockFiles);
        $moduleFileCopier->expects($this->at(1))
            ->method('copyM2Files')
            ->with($files, $key);


        $this->model->moveModuleFiles($namespaces, $codePool);
    }

    /**
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function moveModuleFilesProvider(): array
    {
        return [
            [
                [
                    'Vendor1' => [
                        'Module1' => $this->m2Path . '/Vendor1/Module1/etc/config.xml',
                        'Module2' => $this->m2Path . '/Vendor1/Module2/etc/config.xml',
                        'Module3' => $this->m2Path . '/Vendor1/Module3/etc/config.xml',
                    ],

                    'Vendor2' => [
                        'Module1' => $this->m2Path . '/Vendor2/Module1/etc/config.xml',
                        'Module2' => $this->m2Path . '/Vendor2/Module2/etc/config.xml',
                        'Module3' => $this->m2Path . '/Vendor2/Module3/etc/config.xml',
                    ],
                ],
                'local',
            ],
        ];
    }
}
