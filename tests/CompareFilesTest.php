<?php

namespace Gendiff\CompareFiles;

use PHPUnit\Framework\TestCase;

class CompareFilesTest extends TestCase
{
    public function testCompareFilesWithInner1()
    {
        $nested1 = ['common' => [
            'setting1' => 'value 1'
        ]];
        $nested2 = ['common' => [
            'setting1' => 'value 1']
        ];

        $result1 =
            ['common' => [
                'before' => [],
                'after' => [],
                'noChanges' =>
                    ['setting1' =>
                        ['before' => [], 'after' => [], 'noChanges' => 'value 1']
                    ]

             ]
            ];

        $this->assertEquals($result1, compareFiles($nested1, $nested2));
    }

    public function testCompareFiles()
    {
        $file1 = ['follow' => 'false', 'timeout' => 50, 'port' => '25.35.45'];
        $file2 = ['timeout' => 20, 'port' => '25.35.45', 'user' => '5'];

        $result = [
            'follow' => ['before' => 'false', 'after' => [], 'noChanges' => []],
            'timeout' => ['before' => 50, 'after' => 20, 'noChanges' => []],
            'port' => ['before' => [], 'after' => [], 'noChanges' => '25.35.45'] ,
            'user' => ['before' => [], 'after' => '5', 'noChanges' => []]
            ];

        $this->assertEquals($result, compareFiles($file1, $file2));
    }
}
