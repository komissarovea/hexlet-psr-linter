<?php

namespace HexletPsrLinter;

const BASE_RULES = [
  'methodName' => [
      'stmtType' => 'PhpParser\Node\FunctionLike',
      'function' => 'HexletPsrLinter\checkMethodName',
      'message' => 'Method name is incorrect. Check PSR-2.',
      'needAcc' => false
  ],
  'funcDuplicate' => [
      'stmtType' => 'PhpParser\Node\Stmt\Function_',
      'function' => 'HexletPsrLinter\checkFuncDuplicate',
      'message' => 'Function with such name already exists.',
      'needAcc' => true
  ],
  'propertyName' => [
      'stmtType' => 'PhpParser\Node\Stmt\PropertyProperty',
      'function' => 'HexletPsrLinter\checkVariableName',
      'fixFunction' => 'HexletPsrLinter\fixVariableName',
      'message' => "Property name is incorrect. Use 'camelCase'.",
      'needAcc' => false
  ],
  'variableName' => [
      'stmtType' => 'PhpParser\Node\Expr\Variable',
      'function' => 'HexletPsrLinter\checkVariableName',
      'fixFunction' => 'HexletPsrLinter\fixVariableName',
      'message' => "Variable name is incorrect. Use 'camelCase'.",
      'needAcc' => false
  ],
  'sideEffects' => [
      'stmtType' => 'PhpParser\Node',
      'function' => 'HexletPsrLinter\checkSideEffects',
      'message' => "A file should declare new symbols or execute logic with side effects, but should not do both.",
      'needAcc' => true
  ]
];