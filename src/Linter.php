<?php

namespace HexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Colors\Color;

function lint($input, $autoFix = false)
{
    $errors = [];
    $fixedCode = $input;
    try {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        $rules = loadRules();
        $visitor = new HplNodeVisitor($rules, $autoFix);
        $traverser->addVisitor($visitor);

        $stmts = $parser->parse($input);
        if (count($stmts) === 0 || $stmts[0] instanceof Node\Stmt\InlineHTML) {
            $errors[] = new HplError('error', -1, 'global', null, 'PHP statements were not found.');
        } else {
            $stmts = $traverser->traverse($stmts);
            $errors = $visitor->getErrors();
            if ($autoFix) {
                $prettyPrinter = new PrettyPrinter\Standard;
                // pretty print
                $fixedCode = $prettyPrinter->prettyPrintFile($stmts);
            }
        }
    } catch (\Throwable $e) {
        $errors[] = new HplError('error', -1, 'global', null, $e->getMessage());
    }
    return ['errors' => $errors, 'fixedCode' => $fixedCode];
}

function buildReport($errors)
{
    $output = "";
    $errorsCount = count($errors);
    $footer = "Total errors: $errorsCount" . PHP_EOL;
    $footer = $errorsCount > 0 ? (new Color($footer))->red
      : (new Color($footer))->green;
    if ($errorsCount > 0) {
        $output = array_reduce($errors, function ($acc, $error) {
            $line = (new Color("{$error->getLine()}:"))->blue;
            $errorMark = sprintf("%-7s", $error->getName());
            $errorMark = $error->isFixed() ? (new Color($errorMark))->green : (new Color($errorMark))->red;
            $statement = "Statement: '{$error->getStmtName()}'.";
            $message = (new Color($error->getMessage()))->white;
            //$acc = implode(PHP_EOL, [$acc, "$line $errorMark $message"]);
            $acc = "$acc $line $errorMark $statement $message" . PHP_EOL;
            return $acc;
        }, "");
    }
    $output = "$output $footer";
    //$output = sprintf("%s %s %s", $output, PHP_EOL, $footer);
    return new HplReport($errors, $output);
}
