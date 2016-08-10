<?php

namespace HexletPsrLinter;

class FixTest extends \PHPUnit\Framework\TestCase
{
    public function testVariableNamesWithoutFix()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'BadCode']);
        $result = lint(file_get_contents($path), false);
        $this->assertEquals(7, count($result['errors']));

        $fixedCode = $result['fixedCode'];
        $this->assertNull($fixedCode);
    }

    public function testVariableNamesWithFix()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'variableNames', 'BadCode']);
        $result = lint(file_get_contents($path), true);
        $this->assertEquals(7, count($result['errors']));

        $fixedCode = $result['fixedCode'];
        $this->assertNotNull($fixedCode);

        $result = lint($fixedCode);
        $this->assertEquals(2, count($result['errors']));
    }
}
