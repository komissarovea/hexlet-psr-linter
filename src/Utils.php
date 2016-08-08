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

function getFilesByPath($path)
{
    if (is_readable($path)) {
        if (is_file($path)) {
            return [$path];
        } else {
            $arr = [];
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            foreach ($iterator as $item) {
                if ($item->isFile()) {
                    $arr[] = $item->getPathname();
                }
            }
            // $arr = array_filter(iterator_to_array($iterator), function ($item) {
            //     return $item->isFile();
            // });
            //print_r($arr);
            return $arr;
        }
    } else {
        throw new \HexletPsrLinter\Exceptions\FileException("'$path' is not readable!");
    }
}
