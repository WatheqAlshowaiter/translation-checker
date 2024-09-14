<?php

namespace Bottelet\TranslationChecker\Tests\Extractors;

use Bottelet\TranslationChecker\Extractor\PhpBaseClassExtractor;
use Bottelet\TranslationChecker\Extractor\PhpClassExtractor;
use Bottelet\TranslationChecker\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

use SplFileInfo;

class PhpBaseClassExtractorTest extends TestCase
{
    #[Test]
    public function emptyOnWrongFileType(): void
    {
        $phpExtractor = new PhpBaseClassExtractor();
        $foundStrings = $phpExtractor->extractFromFile($this->vueFile);

        $this->assertEmpty($foundStrings);
    }

    #[Test]
    public function extractFileIsEmptyOnEmptyFile(): void
    {
        $phpExtractor = new PhpBaseClassExtractor();
        $foundStrings = $phpExtractor->extractFromFile($this->vueFile);

        $this->assertEmpty($foundStrings);
    }


    #[Test]
    public function extractFileIsEmptyIfNotTranslationFunctionInFile(): void
    {
        $file = $this->createTempFile('no-translations.php', "
            <?php
            class Test 
            { 
                public function index()
                {
                    return [Model::all()];
                }
            }
        ");
        $phpExtractor = new PhpBaseClassExtractor();
        $foundStrings = $phpExtractor->extractFromFile($file);

        $this->assertEmpty($foundStrings);
    }

    #[Test]
    public function emptyOnNonExistingFiles(): void
    {
        $filePath = $this->tempDir . '/non-existing.php';
        $file = new SplFileInfo($filePath);
        $phpExtractor = new PhpBaseClassExtractor();
        $foundStrings = $phpExtractor->extractFromFile($file);

        $this->assertEmpty($foundStrings);
    }

    #[Test]
    public function getEmptyIfFilePermissionFails(): void
    {
        $filePath = $this->tempDir . '/permission.php';
        file_put_contents($filePath, $this->phpControllerFile);

        chmod($filePath, 0200);

        $file = new SplFileInfo($filePath);
        $phpExtractor = new PhpBaseClassExtractor();
        $this->expectException(\ErrorException::class);
        $phpExtractor->extractFromFile($file);
    }

}