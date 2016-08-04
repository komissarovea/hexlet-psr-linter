<?php

namespace HexletPsrLinter;

class Linter
{
    private $file;
    private $message;

    public function __construct($file)
    {
        $this->file = $file;
        $this->buildMessage();
    }

    private function buildMessage()
    {
        $this->message = "The file '$this->file' is valid.".PHP_EOL;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
