<?php

namespace HexletPsrLinter;

define("MAGIC_METHODS", ['__construct', '__destruct', '__call',
     '__callStatic', '__get', '__set', '__isset', '__unset',
     '__sleep', '__wakeup', '__toString', '__invoke', '__set_state',
     '__clone', '__debugInfo', '__autoload', '__soapcall',
     '__getlastrequest', '__getlastresponse', '__getlastrequestheaders',
     '__getlastresponseheaders', '__getfunctions', '__gettypes',
     '__dorequest', '__setcookie', '__setlocation', '__setsoapheaders']);

function checkMethodName($node)
{
    if (isset($node->name) && !in_array($node->name, MAGIC_METHODS)) {
        return \PHP_CodeSniffer::isCamelCaps($node->name);
    }
    return true;
}

function checkFuncDuplicate($node)
{
     return \PHP_CodeSniffer::isCamelCaps($node->name);
}
