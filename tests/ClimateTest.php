<?php

namespace HexletPsrLinter;

class ClimateTest extends \PHPUnit\Framework\TestCase
{
    public function testHplError()
    {
        $name = 'error';
        $line = 1;
        $stmtName = 'SomeFunction1';
        $node = new \PhpParser\Node\Stmt\Function_($stmtName, array(), ['startLine' => $line]);

        $message = 'Method name is incorrect. Check PSR-2.';
        $rule = ['message' => $message];
        $error = new HplError($node, $rule);

        $this->assertEquals($name, $error->getName());
        $this->assertEquals($line, $error->getLine());
        $this->assertEquals($stmtName, $error->getStmtName());
        $this->assertEquals($message, $error->getMessage());
        $this->assertFalse($error->getFixed());

        $error->setFixed(true);
        $this->assertTrue($error->getFixed());
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
