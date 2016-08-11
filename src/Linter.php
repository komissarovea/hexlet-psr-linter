<?php

namespace HexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Colors\Color;

function lint($input)
{
    $result = [];
    $errors = [];
    try {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        $rules = loadRules();
        $visitor = new HplNodeVisitor($rules);
        $traverser->addVisitor($visitor);

        $stmts = $parser->parse($input);
        if (count($stmts) === 0 || $stmts[0] instanceof Node\Stmt\InlineHTML) {
            $errors[] = new HplError(null, ['message' => 'PHP statements were not found.']);
        } else {
            $stmts = $traverser->traverse($stmts);
            $errors = $visitor->getErrors();
            $result['allStatements'] = $stmts;
        }
    } catch (\Throwable $e) {
        $errors[] = new HplError(null, ['message' => $e->getMessage()]);
    }
    $result['errors'] = $errors;
    return $result;
}

function fix(array $errors, array $allStatements)
{
    foreach ($errors as $error) {
        $rule = $error->getRule();
        if (isset($rule['fixFunction'])) {
            $error->setFixed($rule['fixFunction']($error->getNode()));
        }
    }

    $prettyPrinter = new PrettyPrinter\Standard;
    $fixedCode = $prettyPrinter->prettyPrintFile($allStatements);
    return $fixedCode;
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
            $errorMark = $error->getFixed() ? (new Color($errorMark))->green : (new Color($errorMark))->red;
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
