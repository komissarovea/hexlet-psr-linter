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
class HplNodeVisitor extends NodeVisitorAbstract
{
    private $errors = [];

    public function leaveNode(Node $node)
    {
        if (isset($node->name) && !in_array($node->name, MAGIC_METHODS)
          && $node instanceof Node\FunctionLike
          && !\PHP_CodeSniffer::isCamelCaps($node->name)) {
                  $this->errors[] = new HplError(
                      'error',
                      $node->getLine(),
                      $node->name,
                      get_class($node),
                      "Method name \"$node->name\" is incorrect. Check PSR-2."
                  );
          // $node instanceof Node\Stmt\ClassMethod
          // $node instanceof Node\Stmt\Function_
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
