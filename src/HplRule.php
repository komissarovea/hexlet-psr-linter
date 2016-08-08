<?php

namespace HexletPsrLinter;

/**
 * Check rule logic
 */
class HplRule
{
    private $stmtType;
    private $method;
    private $needAcc;
    private $message;

    public function __construct($stmtType, $method, $message, $needAcc = false)
    {
        $this->stmtType = $stmtType;
        $this->method = $method;
        $this->message = $message;
        $this->needAcc = $needAcc;
    }

    public function getStmtType()
    {
        return $this->stmtType;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getNeedAcc()
    {
        return $this->needAcc;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
