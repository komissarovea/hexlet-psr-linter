<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit\Framework\TestCase
{
    public function testMessageValidFile()
    {
        $file = 'fakeFile';
        $linter = new Linter($file);
        $expected = "The file '$file' is valid.".PHP_EOL;
        var_dump($expected);
        $this->assertEquals($expected, $linter->getMessage());
    }
}
