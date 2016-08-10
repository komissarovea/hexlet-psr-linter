<?php

namespace HexletPsrLinter;

const BASE_RULES = [
  [
      'stmtType' => 'PhpParser\Node\FunctionLike',
      'function' => 'HexletPsrLinter\checkMethodName',
      'message' => 'Method name is incorrect. Check PSR-2.',
      'needAcc' => false,
      'fixable' => false
  ],
  [
      'stmtType' => 'PhpParser\Node\Stmt\Function_',
      'function' => 'HexletPsrLinter\checkFuncDuplicate',
      'message' => 'Function with such name already exists.',
      'needAcc' => true,
      'fixable' => false
  ],
  [
      'stmtType' => 'PhpParser\Node\Stmt\PropertyProperty',
      'function' => 'HexletPsrLinter\checkVariableName',
      'message' => "Property name is incorrect. Use 'camelCase'.",
      'needAcc' => false,
      'fixable' => true
  ],
  [
      'stmtType' => 'PhpParser\Node\Expr\Variable',
      'function' => 'HexletPsrLinter\checkVariableName',
      'message' => "Variable name is incorrect. Use 'camelCase'.",
      'needAcc' => false,
      'fixable' => true
  ],
  [
      'stmtType' => 'PhpParser\Node',
      'function' => 'HexletPsrLinter\checkSideEffects',
      'message' => "A file should declare new symbols or execute logic with side effects, but should not do both.",
      'needAcc' => true,
      'fixable' => false
  ]
];

function loadRules()
{
    return BASE_RULES;
}

function fakeMethod1($x)
{
    echo "fakeMethod1" . strval($x);
}
