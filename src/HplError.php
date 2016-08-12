<?php

namespace HexletPsrLinter;

/**
 * Error logic
 */
class HplError
{
    private $fixed = false;
    private $node;
    private $rule;

    public function __construct($node, array $rule)
    {
        $this->node = $node;
        $this->rule = $rule;
    }

    public function getNode()
    {
        return $this->node;
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function getName()
    {
        return $this->fixed ? "fixed" : "error";
    }

    public function getLine()
    {
        return is_null($this->node) ? -1 : $this->node->getLine();
    }

    public function getStmtName()
    {
        return isset($this->node->name) ? $this->node->name : 'undefined';
    }

    public function getMessage()
    {
        return $this->rule['message'];
    }

    public function getFixed()
    {
        return $this->fixed;
    }

    public function setFixed($value)
    {
        $this->fixed = $value;
    }
}
