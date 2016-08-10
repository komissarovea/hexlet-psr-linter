<?php

namespace HexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

/**
 * Visitor for PhpParser\NodeTraverser
 */
class HplNodeVisitor extends NodeVisitorAbstract
{
    private $rules;
    private $autoFix;

    private $acc;
    private $errors;
    private $lastEndLine;

    public function __construct(array $rules, $autoFix = false)
    {
        $this->rules = $rules;
        $this->autoFix = $autoFix;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->acc = [];
        $this->errors = [];
        $this->lastEndLine = 0;
    }

    public function enterNode(Node $node)
    {
        $nodeName = isset($node->name) ? $node->name : 'undefined';
        //echo get_class($node) . " $nodeName " . PHP_EOL;
        foreach ($this->rules as $rule) {
            $stmtType = $rule['stmtType'];
            if (!array_key_exists($stmtType, $this->acc)) {
                $this->acc[$stmtType] = [];
            }
            if (is_a($node, $stmtType) || is_subclass_of($node, $stmtType)) {
                if (!$rule['function']($node, $this->acc[$stmtType], $this->lastEndLine, $this->autoFix)) {
                    $this->errors[] = new HplError(
                        $rule['fixable'] && $this->autoFix ? 'fixed' : 'error',
                        $node->getLine(),
                        $nodeName,
                        get_class($node),
                        $rule['message'],
                        $rule['fixable'] && $this->autoFix
                    );
                }

                if ($rule['needAcc'] && !in_array($node, $this->acc[$stmtType])) {
                    $this->acc[$stmtType][] = $node;
                    //eval(\Psy\sh());
                }
            }
        }
        $nodeEndLine = $node->getAttribute('endLine');
        if ($nodeEndLine > $this->lastEndLine) {
            $this->lastEndLine = $nodeEndLine;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
