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

    public function testGenDiff()
    {
        $pathToFile1 = 'fixtures/nested/json/file1.json';
        $pathToFile2 = 'fixtures/nested/json/file2.json';

        //$expected = file_get_contents('fixtures/nested/output');
        $expected = <<<'EOD'
{
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: null
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow: 
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
}
EOD;
        $actual = genDiff($pathToFile1, $pathToFile2);

        $this->assertEquals($expected, $actual);
    }
}
