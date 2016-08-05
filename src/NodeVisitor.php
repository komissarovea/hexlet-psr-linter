<?php

namespace HexletPsrLinter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

define("MAGIC_METHODS", ['__construct', '__destruct', '__call',
     '__callStatic', '__get', '__set', '__isset', '__unset',
     '__sleep', '__wakeup', '__toString', '__invoke', '__set_state',
     '__clone', '__debugInfo', '__autoload', '__soapcall',
     '__getlastrequest', '__getlastresponse', '__getlastrequestheaders',
     '__getlastresponseheaders', '__getfunctions', '__gettypes',
     '__dorequest', '__setcookie', '__setlocation', '__setsoapheaders']);

/**
 * Visitor for PhpParser\NodeTraverser
 */
class NodeVisitor extends NodeVisitorAbstract
{
    const CONSTANT = 'значение константы';
    private $methodStmts = [];

    public function leaveNode(Node $node)
    {
        //echo get_class($node).PHP_EOL;

        if ($node instanceof Node\FunctionLike
            && !in_array($node->name, MAGIC_METHODS)) {
              $this->methodStmts[] = $node;
              //echo $node->name.PHP_EOL;
        }
    }

    public function getMethodStmts()
    {
        return $this->methodStmts;
    }
}
