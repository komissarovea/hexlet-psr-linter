<?php

namespace HexletPsrLinter;

class FixTest extends \PHPUnit\Framework\TestCase
{
    public function testVariableNamesWithoutFix()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'BadCode']);
        $code = file_get_contents($path);

        $result = lint($code);
        $this->assertEquals(7, count($result['errors']));

        $result = lint($code);
        $this->assertEquals(7, count($result['errors']));
    }

    public function testVariableNamesWithFix()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'BadCode']);
        $result = lint(file_get_contents($path), true);

        $this->assertEquals(7, count($result['errors']));
        $this->assertArrayHasKey('allStatements', $result);

        $fixedCode = fix($result['errors'], $result['allStatements']);
        $result = lint($fixedCode);

        $this->assertEquals(2, count($result['errors']));
    }
}
