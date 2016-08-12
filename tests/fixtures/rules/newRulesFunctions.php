<?php

namespace SomeNewNamespace;

function checkVariableName2($node)
{
    $result = true;
    if (isset($node->name)) {
        $result = \PHP_CodeSniffer::isCamelCaps($node->name);
    }
    return $result;
}
