<?php

declare(strict_types=1);

namespace Gendiff\Compare;

function compareFiles(array $file1, array $file2): array
{
    $keys1 = array_keys($file1);
    $keys2 = array_keys($file2);
    $allKeys = array_merge($keys1, $keys2);

    $onlyInFirst = '- ';
    $inBoth = '  ';
    $onlyInSecond = '+ ';

    $acc =[];

   foreach ($allKeys as $key) {
       if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
           if (is_array($file1[$key]) && is_array($file2[$key])) {
               $acc[$inBoth . $key] = compareFiles($file1[$key], $file2[$key]);
           } elseif($file1[$key] === $file2[$key]) {
               $acc[$inBoth . $key] = $file1[$key];
           } else {
               $acc[$onlyInFirst . $key] = $file1[$key];
               $acc[$onlyInSecond . $key] = $file2[$key];
           }
       } elseif (array_key_exists($key, $file1)) {
           $acc[$onlyInFirst . $key] = $file1[$key];
       } elseif (array_key_exists($key, $file2)) {
           $acc[$onlyInSecond . $key] = $file2[$key];
       }
   }
   return $acc;
}
/*
function compareTwoValues($value1, $value2)
{
    if (is_array($value1) && is_array($value2)) {
        return compareFiles($value1, $value2);
    } elseif ($value1 === $value2) {

    }
}
*/