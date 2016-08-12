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
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'rules', 'newRules.json']);
        $rules = loadRules($path);
        $this->assertEquals(2, count($rules));
        $this->assertTrue($rules['variableName']['function'](null));

        $node = new \PhpParser\Node\Stmt\PropertyProperty('_Abc');
        $this->assertFalse($rules['propertyName']['function']($node));
    }
}
