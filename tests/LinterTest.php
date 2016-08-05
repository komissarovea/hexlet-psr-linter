<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit\Framework\TestCase
{
    public function testIsReadableFile()
    {
        $linter = new Linter([__DIR__.DIRECTORY_SEPARATOR.'fakeFile'], []);
        $this->assertFalse($linter->lint());

        $linter = new Linter([__DIR__.DIRECTORY_SEPARATOR.'fixtures'], []);
        $this->assertFalse($linter->lint());

        $linter = new Linter([__DIR__.DIRECTORY_SEPARATOR.'LinterTest.php'], []);
        $this->assertTrue($linter->lint());
    }

    public function testLint()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'GoodCode']);
        $linter = new Linter([$path], []);
        $result = $linter->lint();
        //echo $linter;
        $this->assertTrue($result);
        $this->assertEquals(0, count($linter->getErrors()));

        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, 'fixtures', 'methodNames', 'BadCode']);
        $linter = new Linter([$path], []);
        $result = $linter->lint();
        //echo $linter;
        $this->assertFalse($result);
        $this->assertEquals(4, count($linter->getErrors()));
    }
}
