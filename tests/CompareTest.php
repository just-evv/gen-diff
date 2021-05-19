<?php

namespace Gendiff\Compare;

use PHPUnit\Framework\TestCase;

class CompareTest extends TestCase
{
    public function testCompareFiles()
    {
        $file1 = ['follow' => false, 'timeout' => 50, 'port' => '25.35.45'];
        $file2 = ['timeout' => 20, 'port' => '25.35.45', 'user' => '5'];

        $result1 = [
            '- follow' => false, '- timeout' => 50, '+ timeout' => 20, '  port' => '25.35.45', '+ user' => '5'];

        $this->assertEquals($result1, compareFiles($file1, $file2));

        $nested1 = \Gendiff\Gendiff\jsonParse('fixtures/nested/json/file1.json');
        $nested2 = \Gendiff\Gendiff\jsonParse('fixtures/nested/json/file2.json');

        $result2 = compareFiles($nested1, $nested2);
        print_r($result2);

    }
}
