<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit\Framework\TestCase
{
    public function testLintMethodNames()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'GoodCode']);
        $linter = new Linter(file_get_contents($path));
        $report = $linter->lint();
        $this->assertNotNull($report->getOutput());
        $this->assertEquals(0, count($report->getErrors()));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'BadCode']);
        $linter = new Linter(file_get_contents($path));
        $report = $linter->lint();
        $this->assertNotNull($report->getOutput());
        $this->assertEquals(5, count($report->getErrors()));
    }
}
