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
    private $acc;
    private $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->acc = [];
    }

    public function leaveNode(Node $node)
    {
        foreach ($this->rules as $rule) {
            $stmtType = $rule->getStmtType();
            if (!array_key_exists($stmtType, $this->acc)) {
                $this->acc[$stmtType] = [];
            }
            if (is_a($node, $stmtType) || is_subclass_of($node, $stmtType)) {
                if (!$rule->getMethod()($node, $this->acc[$stmtType])) {
                    $this->errors[] = new HplError(
                        'error',
                        $node->getLine(),
                        $node->name,
                        get_class($node),
                        $rule->getMessage()
                    );
                }

                if ($rule->getNeedAcc() && !in_array($node, $this->acc[$stmtType])) {
                    $this->acc[$stmtType][] = $node;
                    //eval(\Psy\sh());
                }
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
