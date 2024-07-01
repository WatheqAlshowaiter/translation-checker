<?php

namespace Bottelet\TranslationChecker\Tests;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public string $tempDir;
    protected SplFileInfo $bladeFile;
    protected SplFileInfo $phpControllerFile;
    protected SplFileInfo $vueFile;
    protected SplFileInfo $noTranslationsBladeFile;

    protected function setUp(): void
    {
        parent::setUp();
        error_reporting(E_ALL);
        $this->tempDir = sys_get_temp_dir() . '/bottelet-translation-checker-test';
        if (! file_exists($this->tempDir)) {
            mkdir($this->tempDir, 0777, true);
        }

        $this->createTemplateFiles();
    }

    protected function tearDown(): void
    {
        $iterator    = new RecursiveDirectoryIterator($this->tempDir, FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file instanceof SplFileInfo && $file->isFile()) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
        }
        rmdir($this->tempDir);

        parent::tearDown();
    }

    public function createTemplateFiles(): void
    {
        $bladeFilePath = $this->tempDir . '/test.blade.php';
        $bladePath = __DIR__ . '/templates/underscore-translations.blade.php';
        file_put_contents($bladeFilePath, file_get_contents($bladePath));

        $phpController = $this->tempDir . '/TestController.php';
        $phpControllerPath =  __DIR__ .  '/templates/TestController.php';
        file_put_contents($phpController, file_get_contents($phpControllerPath));

        $vueFilePath = $this->tempDir . '/test.vue';
        $vuePath =  __DIR__ . '/templates/dollar-t.vue';
        file_put_contents($vueFilePath, file_get_contents($vuePath));

        $noTranslationsFile = $this->tempDir . '/empty.blade.php';
        $noTranslationsPath =  __DIR__ . '/templates/no-translations.blade.php';
        file_put_contents($noTranslationsFile, file_get_contents($noTranslationsPath));

        $this->bladeFile = new SplFileInfo($bladeFilePath);
        $this->phpControllerFile = new SplFileInfo($phpControllerPath);
        $this->vueFile = new SplFileInfo($vueFilePath);
        $this->noTranslationsBladeFile = new SplFileInfo($noTranslationsPath);

    }
}