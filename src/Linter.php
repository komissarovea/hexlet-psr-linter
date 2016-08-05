<?php

namespace HexletPsrLinter;

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
        //$c = new Color();
        //echo $c->__invoke('Hello World!')->white()->bold()->highlight('green').PHP_EOL;
        //echo $c('Hello World!')->white->bold->bg_blue.PHP_EOL;

        $this->args = $args;
        $this->flags = $flags;
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();
        $this->visitor = new NodeVisitor();
        $this->traverser->addVisitor($this->visitor);
        $this->color = new Color();
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function lint()
    {
        $filePath = $this->args[0];
        if (is_readable($filePath) && is_file($filePath)) {
            $this->output = (new Color($filePath))->white->underline.PHP_EOL;
            $code = file_get_contents($filePath);
            try {
                $stmts = $this->parser->parse($code);
                $stmts = $this->traverser->traverse($stmts);
                foreach ($this->visitor->getMethodStmts() as $node) {
                    $line = $node->getLine().": ";
                    $this->output .= (new Color($line))->blue;
                    if (\PHP_CodeSniffer::isCamelCaps($node->name)) {
                        $this->success[] = $node;
                        $this->output .= (new Color(sprintf("%-8s", 'success ')))->green;
                        $text = "Method name \"$node->name\" is correct";

                        $this->output .= (new Color($text))->white.PHP_EOL;
                    } else {
                        $this->error[] = $node;
                        $this->output .= (new Color(sprintf("%-8s", 'error ')))->red;
                        $text = "Method name \"$node->name\" is incorrect";
                        $this->output .= (new Color($text))->white.PHP_EOL;
                    }
                }

                //$prettyPrinter = new PrettyPrinter\Standard;
                // $code2 = $prettyPrinter->prettyPrint($stmts);
                // echo $code2;
            } catch (Error $e) {
                $this->output = 'Parse Error: '.$e->getMessage().PHP_EOL;
                $this->output = (new Color($this->output))->red;
                //echo 'Parse Error: ', $e->getMessage();
            }
        } else {
            $this->output = "The file '$filePath' is not readable file.".PHP_EOL;
            $this->output = (new Color($this->output))->red;
        }
    }

    // public function __call($name, $args)
    // {
    //     return call_user_func_array($this->$name, $args);
    // }

    // public function _fake1()
    // {
    //     return $this->getOutput();
    // }
    //
    // public function Fake2()
    // {
    //     return $this->getOutput();
    // }

    public function __toString()
    {
        return $this->getOutput();
    }
}
