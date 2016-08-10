<?php

namespace HexletPsrLinter;

use function \HexletPsrLinter\Utils\getFilesByPath;

class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testPathNotExists()
    {
        $this->expectException(\HexletPsrLinter\Exceptions\FileException::class);
        getFilesByPath("fakeFile");
    }

    public function testNotReadableFile()
    {
        $this->expectException(\HexletPsrLinter\Exceptions\FileException::class);
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'NotReadableFile']);
        getFilesByPath($path);
    }

    public function testFilePath()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'DuplicatedFunctions']);
        $this->assertEquals(1, count(getFilesByPath($path)));
    }

    public function testDirPath()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames']);
        $this->assertEquals(2, count(getFilesByPath($path)));
    }
}
