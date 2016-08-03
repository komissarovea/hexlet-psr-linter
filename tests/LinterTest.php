<?php

namespace HexletPsrLinter;

class LinterTest extends \PHPUnit\Framework\TestCase
{
    public function testMessageEmptyArgs()
    {
        $linter = new Linter(null);
        $expected = $linter->getUsageInfo();
        $this->assertEquals($expected, $linter->getMessage());
    }

    public function testMessageFileNotExists()
    {
        $file = 'fakeFile';
        $args = array('binFile', $file);
        $linter = new Linter($args);
        $expected = "ERROR: The file '$file' does not exist.".PHP_EOL;
        var_dump($expected);
        $this->assertEquals($expected, $linter->getMessage());
    }
}
