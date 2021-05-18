<?php
namespace Gendiff\Gendiff;

use PHPUnit\Framework\TestCase;

class GendiffTest extends TestCase
{
    public function testCompareFiles()
    {
        $file1 = ['follow' => false, 'timeout' => 50, 'port' => '25.35.45'];
        $file2 = ['timeout' => 20, 'port' => '25.35.45', 'user' => '5'];

        $result1 = '    port = 25.35.45';
        $this->assertEquals($result1, compareFiles('port', $file1, $file2));

        $result2 = '  + user = 5';
        $this->assertEquals($result2, compareFiles('user', $file1, $file2));

        $result3 = "  - timeout = 50\n  + timeout = 20";
        $this->assertEquals($result3, compareFiles('timeout', $file1, $file2));
    }





}
