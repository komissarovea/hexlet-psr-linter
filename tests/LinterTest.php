<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit\Framework\TestCase
{
    public function testMethodNames()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'GoodCode']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(0, count($errors));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'BadCode']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(5, count($errors));
    }

    public function testFunctionDuplicates()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'DuplicatedFunctions']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(1, count($errors));
    }
}
