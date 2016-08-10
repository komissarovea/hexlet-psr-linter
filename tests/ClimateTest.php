<?php

namespace HexletPsrLinter;

use function \HexletPsrLinter\Utils\getFilesByPath;

class ClimateTest extends \PHPUnit\Framework\TestCase
{
    public function testHplError()
    {
        $name = 'name';
        $line = 1;
        $stmtName = 'someMethod1';
        $stmtType = 'PhpParser\Node\FunctionLike';
        $message = 'Method name is incorrect. Check PSR-2.';
        $fixed = true;
        $error = new HplError($name, $line, $stmtName, $stmtType, $message, $fixed);
        $this->assertEquals($name, $error->getName());
        $this->assertEquals($line, $error->getLine());
        $this->assertEquals($stmtName, $error->getStmtName());
        $this->assertEquals($stmtType, $error->getStmtType());
        $this->assertEquals($message, $error->getMessage());
        $this->assertEquals($fixed, $error->isFixed());
    }

    public function testHplReport()
    {
        $errors = [];
        $output = " Total errors: 0" . PHP_EOL;
        $report = new HplReport($errors, $output);
        $this->assertEquals($errors, $report->getErrors());
        $this->assertEquals($output, $report->getOutput());
    }
}
