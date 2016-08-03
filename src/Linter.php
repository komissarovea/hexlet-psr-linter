<?php

namespace HexletPsrLinter;

class Linter
{
    private $bin;
    private $file;
    private $args;
    private $message;

    public function __construct($argv)
    {
        $this->args = $argv;
        if (!empty($this->args)) {
            $this->bin = $this->args[0];
            array_shift($this->args);
            var_dump($this->args);
            if (count($this->args) > 0) {
                $this->file = $this->args[0];
            }
        }
        $this->buildMessage();
    }

    private function buildMessage()
    {
        if (empty($this->args)) {
            $this->message = $this->getUsageInfo();
        } else {
            $this->message = "ERROR: The file '$this->file' does not exist.".PHP_EOL;
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getUsageInfo()
    {
        return 'Usage: hexlet-psr-linter [options] <file> '.PHP_EOL.
        '  <file>        One or more files and/or directories to check'.PHP_EOL.
        '  --help        Print this help message'.PHP_EOL.
        '  --version     Print version information'.PHP_EOL;
    }
}
