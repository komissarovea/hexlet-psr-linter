<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit\Framework\TestCase
{
    public function testMethodNames()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'GoodCode']);
        $result = lint(file_get_contents($path));
        $this->assertEquals(0, count($result['errors']));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'BadCode']);
        $result = lint(file_get_contents($path));
        $this->assertEquals(5, count($result['errors']));
    }

    public function testVariableNames()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'GoodCode']);
        $result = lint(file_get_contents($path));
        $this->assertEquals(0, count($result['errors']));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'BadCode']);
        $result = lint(file_get_contents($path));
        $this->assertEquals(7, count($result['errors']));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'InvalidCode']);
        $result = lint(file_get_contents($path));
        $errors = $result['errors'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals('undefined', $errors[0]->getStmtName());
    }

    public function testFunctionDuplicates()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'DuplicatedFunctions']);
        $result = lint(file_get_contents($path));
        $this->assertEquals(1, count($result['errors']));
    }

    public function testSideEffects()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'sideEffects', 'GoodCode1']);
        $result = lint(file_get_contents($path));
        $this->assertEquals(0, count($result['errors']));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'sideEffects', 'GoodCode2']);
        $result = lint(file_get_contents($path));
        $this->assertEquals(0, count($result['errors']));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'sideEffects', 'BadCode']);
        $result = lint(file_get_contents($path));
        $this->assertEquals(3, count($result['errors']));
    }

    public function testInvalidFile()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'InvalidPHP']);
        $result = lint(file_get_contents($path));
        $errors = $result['errors'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals('undefined', $errors[0]->getStmtName());
    }

    public function testNotPhpFile()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'NotPHP']);
        $result = lint(file_get_contents($path));
        $errors = $result['errors'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals('undefined', $errors[0]->getStmtName());
    }
}
