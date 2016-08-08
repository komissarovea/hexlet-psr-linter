<?php

namespace HexletPsrLinter;

/**
 * Error logic
 */
class HplError
{
    private $name;
    private $line;
    private $stmtName;
    private $stmtType;
    private $message;

    public function __construct($name, $line, $stmtName, $stmtType, $message)
    {
        $this->name = $name;
        $this->line = $line;
        $this->stmtName = $stmtName;
        $this->stmtType = $stmtType;
        $this->message = $message;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getStmtName()
    {
        return $this->stmtName;
    }

    public function getStmtType()
    {
        return $this->stmtType;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
