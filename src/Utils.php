<?php

namespace HexletPsrLinter\Utils;

use Colors\Color;

function formatErrorMessage($message, $addEol = true)
{
    $result = (new Color("ERROR: ".$message))->red;
    if ($addEol) {
        $result .= PHP_EOL;
    }
    return $result;
}

function getFilesByPath($path)
{
    if (is_readable($path)) {
        if (is_file($path)) {
            return [$path];
        } else {
            $arr = [];
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($iterator as $item) {
                if ($item->isFile() && $item->isReadable()) {
                    $arr[] = $item->getPathname();
                }
            }
            return $arr;
        }
    } else {
        throw new \HexletPsrLinter\Exceptions\FileException("'$path' is not readable!");
    }
}
