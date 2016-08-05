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
    private $args;
    private $flags;
    private $parser;
    private $traverser;
    private $visitor;

    private $errors = [];
    private $success = [];
    private $output = "";

    public function __construct($args, $flags)
    {
        $this->args = $args;
        $this->flags = $flags;
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();
        $this->visitor = new NodeVisitor();
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
        $this->output = "";
        $filePath = $this->args[0];
        if (is_readable($filePath) && is_file($filePath)) {
            $this->output = (new Color($filePath))->white->underline.PHP_EOL;
            $code = file_get_contents($filePath);
            try {
                $stmts = $this->parser->parse($code);
                if (count($stmts) === 0 || $stmts[0] instanceof Node\Stmt\InlineHTML) {
                    $message = "PHP statements were not found in the file!".PHP_EOL;
                    $this->output .= (new Color($message))->red;
                    return false;
                }
                $this->traverser->traverse($stmts);
                foreach ($this->visitor->getMethodStmts() as $node) {
                    $this->processMethodStmt($node);
                }
                $errorsCount = count($this->errors);
                if ($errorsCount > 0) {
                    $message = "Total errors: $errorsCount".PHP_EOL;
                    $this->output .= (new Color($message))->red;
                    return false;
                }
            } catch (Error $e) {
                $message = 'Parse Error: '.$e->getMessage().PHP_EOL;
                $this->output = (new Color($message))->red;
                return false;
            }
        } else {
            $message = "'$filePath' is not readable file".PHP_EOL;
            $this->output = (new Color($message))->red;
            return false;
        }
        return true;
    }

    private function processMethodStmt($node)
    {
        $line = $node->getLine().": ";
        $this->output .= (new Color($line))->blue;
        if (\PHP_CodeSniffer::isCamelCaps($node->name)) {
            $this->success[] = $node;
            $this->output .= (new Color(sprintf("%-8s", 'success')))->green;
            $message = "Method name \"$node->name\" is correct.";
            $this->output .= (new Color($message))->white.PHP_EOL;
        } else {
            $this->errors[] = $node;
            $this->output .= (new Color(sprintf("%-8s", 'error')))->red;
            $message = "Method name \"$node->name\" is incorrect. Check PSR-2.";
            $this->output .= (new Color($message))->white.PHP_EOL;
        }
    }

    public function __toString()
    {
        return strval($this->output);
    }
}
