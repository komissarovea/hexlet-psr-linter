<?php

namespace HexletPsrLinter\Utils;

use Colors\Color;

function formatErrorMessage($message, $addEOL = true)
{
    $result = (new Color("ERROR: ".$message))->red;
    if ($addEOL) {
        $result .= PHP_EOL;
    }
    return $result;
}

function readCliFileContent(&$filePath)
{
    $cmd = new \Commando\Command();
    $cmd->doNotTrapErrors();
    $cmd->argument()
        ->title('<file>')
        ->describedAs('A file to lint.')
        ->expectsFile()
        ->require();
    $args = $cmd->getArgumentValues();
    $flags = $cmd->getFlagValues();
    $filePath = $args[0];
    if (is_readable($filePath) && is_file($filePath)) {
        return file_get_contents($filePath);
    } else {
        throw new \HexletPsrLinter\HplException("'$filePath' is not readable file", 1);
        // echo formatErrorMessage("'$filePath' is not readable file");
        // return 1;
    }
}
