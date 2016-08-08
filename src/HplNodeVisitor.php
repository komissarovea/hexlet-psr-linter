<?php

namespace HexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * Visitor for PhpParser\NodeTraverser
 */
class HplNodeVisitor extends NodeVisitorAbstract
{
    private $errors = [];
    private $rules;

    public function __construct($rules)
    {
        $this->rules = $rules;
    }

    public function leaveNode(Node $node)
    {
        foreach ($this->rules as $rule) {
            //print_r($rule);
            if (is_subclass_of($node, $rule->getStmtType())
              && !$rule->getMethod()($node)) {
                $this->errors[] = new HplError(
                    'error',
                    $node->getLine(),
                    $node->name,
                    get_class($node),
                    $rule->getMessage()
                );
            }
        }

        // if (isset($node->name) && !in_array($node->name, MAGIC_METHODS)
        //   && $node instanceof Node\FunctionLike
        //   && !\PHP_CodeSniffer::isCamelCaps($node->name)) {
        //           //var_dump(is_subclass_of($node, 'PhpParser\Node\FunctionLike'));
        //           $this->errors[] = new HplError(
        //               'error',
        //               $node->getLine(),
        //               $node->name,
        //               get_class($node),
        //               "Method name \"$node->name\" is incorrect. Check PSR-2."
        //           );
        //   // $node instanceof Node\Stmt\ClassMethod
        //   // $node instanceof Node\Stmt\Function_
        // }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
