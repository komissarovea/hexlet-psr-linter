<?php

namespace HexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Colors\Color;

/**
 * Main logic
 */
class Linter
{
    private $parser;
    private $traverser;
    private $visitor;

    private $input = "";
    private $errors = [];
    private $output = "";

    public function __construct($input)
    {
        $this->input = $input;
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();
        $this->visitor = new HplNodeVisitor();
        $this->traverser->addVisitor($this->visitor);
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function lint()
    {
        $stmts = $this->parser->parse($this->input);
        if (count($stmts) === 0 || $stmts[0] instanceof Node\Stmt\InlineHTML) {
            throw new HplException("PHP statements were not found!");
        }
        $this->traverser->traverse($stmts);
        $methodStmts = $this->visitor->getMethodStmts();

        $this->errors = array_filter($methodStmts, function ($node) {
            return !\PHP_CodeSniffer::isCamelCaps($node->name);
        });

        $this->output = array_reduce($this->errors, function ($acc, $node) {
            $line = $node->getLine().": ";
            $acc .= (new Color($line))->blue;
            $acc .= (new Color(sprintf("%-8s", 'error')))->red;
            $message = "Method name \"$node->name\" is incorrect. Check PSR-2.";
            $acc .= (new Color($message))->white.PHP_EOL;
            return $acc;
        }, "");
        
        $errorsCount = count($this->errors);
        $message = "Total errors: $errorsCount".PHP_EOL;
        if ($errorsCount > 0) {
            $this->output .= (new Color($message))->red;
            return false;
        }
        $this->output .= (new Color($message))->green;
        return true;
    }

    public function __toString()
    {
        return strval($this->getOutput());
    }
}
