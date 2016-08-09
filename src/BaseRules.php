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

define("BASE_RULES", [
  [
      'stmtType' => 'PhpParser\Node\FunctionLike',
      'function' => 'HexletPsrLinter\checkMethodName',
      'message' => 'Method name is incorrect. Check PSR-2.',
      'needAcc' => false
  ],
  [
      'stmtType' => 'PhpParser\Node\Stmt\Function_',
      'function' => 'HexletPsrLinter\checkFuncDuplicate',
      'message' => 'Function with such name already exists.',
      'needAcc' => true
  ],
  [
      'stmtType' => 'PhpParser\Node\Stmt\PropertyProperty',
      'function' => 'HexletPsrLinter\checkVariableName',
      'message' => "Property name is incorrect. Use 'camelCase'.",
      'needAcc' => false
  ],
  [
      'stmtType' => 'PhpParser\Node\Expr\Variable',
      'function' => 'HexletPsrLinter\checkVariableName',
      'message' => "Variable name is incorrect. Use 'camelCase'.",
      'needAcc' => false
  ],
  [
      'stmtType' => 'PhpParser\Node',
      'function' => 'HexletPsrLinter\checkSideEffects',
      'message' => "A file should declare new symbols or execute logic with side effects, but should not do both.",
      'needAcc' => true
  ]
]);

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

function checkSideEffects($node, array $acc)
{
    $nodeClass = get_class($node);
    if (in_array($nodeClass, STMT_TYPES)
      || (in_array($nodeClass, EXPR_TYPES) && null === getParent($node, $acc))) {
        //$nodeName = isset($node->name) ? $node->name : 'undefined';
        //echo $nodeClass . " $nodeName " . PHP_EOL;
        $conflictItems = array_filter($acc, function ($item) use ($nodeClass, $acc) {
            $itemClass = get_class($item);
            if (in_array($nodeClass, EXPR_TYPES)) {
                return in_array($itemClass, STMT_TYPES);
            }
            if (in_array($nodeClass, STMT_TYPES)) {
                return in_array($itemClass, EXPR_TYPES) && null === getParent($item, $acc);
            }
            return false;
        });

        return count($conflictItems) === 0;
    }
    return true;
}

function getParent($node, array $acc)
{
    foreach ($acc as $item) {
        if (isset($item->stmts) && in_array($node, $item->stmts)) {
            return $item;
        }
    }
    return null;
}
