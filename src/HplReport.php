<?php

namespace HexletPsrLinter;

/**
 * Report logic
 */
class HplReport
{
    private $errors;
    private $output;

    public function __construct($errors, $output)
    {
        $this->errors = $errors;
        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
