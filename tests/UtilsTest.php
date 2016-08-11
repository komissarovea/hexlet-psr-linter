<?php

namespace HexletPsrLinter;

use function \HexletPsrLinter\Utils\getFilesByPath;
use function \HexletPsrLinter\Utils\loadRules;
use function \HexletPsrLinter\Utils\strToCamelCase;

class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testPathNotExists()
    {
        $this->expectException(\HexletPsrLinter\Exceptions\FileException::class);
        getFilesByPath("fakeFile");
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

    public function testStrToCamelCase()
    {
        $this->assertEquals("myVarName", strToCamelCase("_my_var_name"));
        $this->assertEquals("myVarName", strToCamelCase("My_varNAME"));
    }

    public function testLoadRules()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'testRules.json']);
        $this->assertEquals(3, count(loadRules($path)));
    }
}
