<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit\Framework\TestCase
{
    public function testLintMethodNames()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'GoodCode']);
        $report = lint(file_get_contents($path));
        $this->assertNotNull($report->getOutput());
        $this->assertEquals(0, count($report->getErrors()));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'BadCode']);
        $report = lint(file_get_contents($path));
        $this->assertNotNull($report->getOutput());
        $this->assertEquals(5, count($report->getErrors()));
    }
}
