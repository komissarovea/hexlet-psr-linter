<?php

namespace HexletPsrLinter;

const BASE_RULES = [
  'methodName' => [
      'stmtType' => 'PhpParser\Node\FunctionLike',
      'functionsFile' => 'src/Rules/RulesFunctions.php',
      'function' => 'HexletPsrLinter\checkMethodName',
      'message' => 'Method name is incorrect. Check PSR-2.',
      'needAcc' => false
  ],
  'funcDuplicate' => [
      'stmtType' => 'PhpParser\Node\Stmt\Function_',
      'functionsFile' => 'src/Rules/RulesFunctions.php',
      'function' => 'HexletPsrLinter\checkFuncDuplicate',
      'message' => 'Function with such name already exists.',
      'needAcc' => true
  ],
  'propertyName' => [
      'stmtType' => 'PhpParser\Node\Stmt\PropertyProperty',
      'functionsFile' => 'src/Rules/RulesFunctions.php',
      'function' => 'HexletPsrLinter\checkVariableName',
      'fixFunction' => 'HexletPsrLinter\fixVariableName',
      'message' => "Property name is incorrect. Use 'camelCase'.",
      'needAcc' => false
  ],
  'variableName' => [
      'stmtType' => 'PhpParser\Node\Expr\Variable',
      'functionsFile' => 'src/Rules/RulesFunctions.php',
      'function' => 'HexletPsrLinter\checkVariableName',
      'fixFunction' => 'HexletPsrLinter\fixVariableName',
      'message' => "Variable name is incorrect. Use 'camelCase'.",
      'needAcc' => false
  ],
  'sideEffects' => [
      'stmtType' => 'PhpParser\Node',
      'functionsFile' => 'src/Rules/RulesFunctions.php',
      'function' => 'HexletPsrLinter\checkSideEffects',
      'message' => "A file should declare new symbols or execute logic with side effects, but should not do both.",
      'needAcc' => true
  ]
];
