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

    public function testVariableNames()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'GoodCode']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(0, count($errors));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'BadCode']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(7, count($errors));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'InvalidCode']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(1, count($errors));
        $this->assertEquals('global', $errors[0]->getStmtName());
    }

    public function testFunctionDuplicates()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'DuplicatedFunctions']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(1, count($errors));
    }

    public function testSideEffects()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'sideEffects', 'GoodCode1']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(0, count($errors));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'sideEffects', 'GoodCode2']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(0, count($errors));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'sideEffects', 'BadCode']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(3, count($errors));
    }

    public function testInvalidFile()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'InvalidPHP']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(1, count($errors));
        $this->assertEquals('global', $errors[0]->getStmtName());
    }

    public function testNotPhpFile()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'NotPHP']);
        $errors = lint(file_get_contents($path));
        $this->assertEquals(1, count($errors));
        $this->assertEquals('global', $errors[0]->getStmtName());
    }
}
