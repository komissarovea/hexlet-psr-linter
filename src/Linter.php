<?php

namespace HexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Colors\Color;
use Symfony\Component\Yaml\Yaml;

function lint($input, $rules = BASE_RULES)
{
    $result = [];
    $errors = [];
    try {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
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

function buildReport($errors, $format)
{
    $report = "";
    $dict = convertErrorsToDictionary($errors);
    switch ($format) {
        case 'json':
            $report = json_encode($dict);
            break;
        case 'yml':
            $report = Yaml::dump($dict);
            break;
        default:
            $report = array_reduce($dict['errors'], function ($acc, $error) {
                $line = (new Color("$error[line]:"))->blue;
                $name = sprintf("%-7s", $error['name']);
                $name = $error['fixed'] ? (new Color($name))->green : (new Color($name))->red;
                $statement = "Statement: '$error[statement]'.";
                $message = (new Color($error['message']))->white;
                $acc = "$acc $line $name $statement $message" . PHP_EOL;
                return $acc;
            }, "");
            $footer = "Total errors: $dict[totalErrors]" . PHP_EOL;
            $footer = $dict['totalErrors'] > 0 ? (new Color($footer))->red : (new Color($footer))->green;
            $report = "$report $footer";
            //break;
    }
    return $report;
}

function convertErrorsToDictionary($errors)
{
    $dict = [];
    $dict['errors'] = array_map(function ($error) {
        return [
            'line' => $error->getLine(),
            'name' => $error->getName(),
            'fixed' => $error->getFixed(),
            'statement' => $error->getStmtName(),
            'message' => $error->getMessage()
        ];
    }, $errors);
    $dict['totalErrors'] = count($errors);

    return $dict;
}
