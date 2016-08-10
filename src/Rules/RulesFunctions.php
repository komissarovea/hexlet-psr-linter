<?php

namespace HexletPsrLinter;

define("MAGIC_METHODS", ['__construct', '__destruct', '__call',
     '__callStatic', '__get', '__set', '__isset', '__unset',
     '__sleep', '__wakeup', '__toString', '__invoke', '__set_state',
     '__clone', '__debugInfo', '__autoload', '__soapcall',
     '__getlastrequest', '__getlastresponse', '__getlastrequestheaders',
     '__getlastresponseheaders', '__getfunctions', '__gettypes',
     '__dorequest', '__setcookie', '__setlocation', '__setsoapheaders']);

define("STMT_TYPES", [
    'PhpParser\Node\Stmt\Class_',
    'PhpParser\Node\Stmt\Function_',
    'PhpParser\Node\Stmt\Const_',
    'PhpParser\Node\Const_']);

define("EXPR_TYPES", [
    'PhpParser\Node\Expr\Variable',
    'PhpParser\Node\Expr\FuncCall',
    'PhpParser\Node\Stmt\Echo_']);

function checkMethodName($node)
{
    if (isset($node->name) && !in_array($node->name, MAGIC_METHODS)) {
        return \PHP_CodeSniffer::isCamelCaps($node->name);
    }
    return true;
}

function checkFuncDuplicate($node, array $acc)
{
    //eval(\Psy\sh());
    $doubles = array_filter($acc, function ($item) use ($node) {
        return $item->name === $node->name;
    });
    return count($doubles) === 0;
}

function checkVariableName($node)
{
    if (isset($node->name)) {
        return \PHP_CodeSniffer::isCamelCaps($node->name);
    }
    return true;
}

function checkSideEffects($node, array $acc, $lastEndLine)
{
    if (isStatement($node) || isRootExpression($node, $lastEndLine)) {
        // $nodeName = isset($node->name) ? $node->name : 'undefined';
        // echo get_class($node) . " $nodeName " . PHP_EOL;
        $internalEndLine = 0;
        $conflictItems = array_filter($acc, function ($item) use ($node, &$internalEndLine) {
            $result = isStatement($node) ? isRootExpression($item, $internalEndLine)
              : isStatement($item);
            $itemEndLine = $item->getAttribute('endLine');
            if ($itemEndLine > $internalEndLine) {
                $internalEndLine = $itemEndLine;
            }
            return $result;
        });

        return count($conflictItems) === 0;
    }
    return true;
}

function isStatement($node)
{
    $nodeClass = get_class($node);
    return in_array($nodeClass, STMT_TYPES)
      || (isset($node->name) && $node->name == 'define');
}

function isRootExpression($node, $lastEndLine = 0)
{
    $nodeClass = get_class($node);
    return in_array($nodeClass, EXPR_TYPES) && !hasParent($node, $lastEndLine)
      && !(isset($node->name) && $node->name == 'define');
}

function hasParent($node, $lastEndLine)
{
    $nodeEndLine = $node->getAttribute('endLine');
    //eval(\Psy\sh());
    return $nodeEndLine < $lastEndLine;
}

// Not fully implemented
function getParent($node, array $acc)
{
    //eval(\Psy\sh());
    foreach ($acc as $item) {
        if ((isset($item->stmts) && in_array($node, $item->stmts))
            || (isset($item->exprs) && in_array($node, $item->exprs))) {
            return $item;
        }
    }
    return null;
}
