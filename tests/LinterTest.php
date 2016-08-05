<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit\Framework\TestCase
{
    public function testLintMethodNames()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'GoodCode']);
        $linter = new Linter(file_get_contents($path));
        $result = $linter->lint();
        //echo $linter;
        $this->assertTrue($result);
        $this->assertEquals(0, count($linter->getErrors()));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'BadCode']);
        $linter = new Linter(file_get_contents($path));
        $result = $linter->lint();
        //echo $linter;
        $this->assertFalse($result);
        $this->assertEquals(5, count($linter->getErrors()));
    }
}
