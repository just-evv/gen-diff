<?php

namespace Gendiff\Compare;

use PHPUnit\Framework\TestCase;

class CompareTest extends TestCase
{
    public function testCompareFilesFlat()
    {
        $file1 = ['follow' => false, 'timeout' => 50, 'port' => '25.35.45'];
        $file2 = ['timeout' => 20, 'port' => '25.35.45', 'user' => '5'];

        $result = [
            '- follow' => false, '- timeout' => 50, '+ timeout' => 20, '  port' => '25.35.45', '+ user' => '5'];

        $this->assertEquals($result, compareFiles($file1, $file2));
    }

    public function testCompareFiles()
    {
        $nested1 = ['common' => [
                        'setting1' => 'value 1',
                        'setting2' => [
                            'setting3' => 'value 3']],
                    'group 1' => ['setting4' => 'value 4'],
                    'key' => 'value'];

        $nested2 = ['common' => [
                        'setting1' => 'value 1',
                        'setting2' => false],
                    'group 1' => ['setting4' => 16],
                    'key2' => 'value'];

        $result = ['  common' => [
            '  setting1' => 'value 1',
            '- setting2' => [
                'setting3' => 'value 3'],
            '+ setting2' => false],
            '  group 1' => [
                '- setting4' => 'value 4',
                '+ setting4' => 16],
            '- key' => 'value',
            '+ key2' => 'value'];

        $this->assertEquals($result, compareFiles($nested1, $nested2));
    }

    public function testCompareFilesWithInner3()
    {
        $nested1 = ['common' => [
            'setting1' => 'value 1',
            'setting2' => [
                'setting3' => 'value 3']]];

        $nested2 = ['common' => [
            'setting1' => 'value 1',
            'setting2' => 7]
            ];

        $result3 = ['  common' => [
            '  setting1' => 'value 1',
            '- setting2' => ['setting3' => 'value 3'],
            '+ setting2' => 7
        ]];

        $this->assertEquals($result3, compareFiles($nested1, $nested2));
    }


    public function testCompareFilesWithInner2()
    {
        $nested1 = ['common' => [
            'setting1' => 'value 1'
        ]];
        $nested2 = ['common' => [
            'setting1' => 'value 2']
        ];

        $result2 = ['  common' => [
            '- setting1' => 'value 1',
            '+ setting1' => 'value 2'
        ]];
        print_r(compareFiles($nested1, $nested2));

        $this->assertEquals($result2, compareFiles($nested1, $nested2));
    }

    public function testCompareFilesWithInner1()
    {
        $nested1 = ['common' => [
            'setting1' => 'value 1'
           ]];
        $nested2 = ['common' => [
            'setting1' => 'value 1']
            ];

        $result1 = ['  common' => [
            '  setting1' => 'value 1'
            ]];
        print_r(compareFiles($nested1, $nested2));

        $this->assertEquals($result1, compareFiles($nested1, $nested2));
    }
}
