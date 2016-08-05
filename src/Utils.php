<?php

namespace HexletPsrLinter\Utils;

use Colors\Color;

function iam()
{
    return "example\n";
}

function formatErrorMessage($message, $addEOL = true)
{
    $result = strval((new Color($message))->red);
    if ($addEOL) {
        $result .= PHP_EOL;
    }
    return $result;
}
