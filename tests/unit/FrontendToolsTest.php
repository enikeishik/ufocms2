<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Config;
use \Ufocms\Frontend\ToolsPath;
use \Ufocms\Frontend\Tools;
 
class FrontendToolsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var Tools
     */
    protected $tools;
    
    protected function _before()
    {
        $config = new Config();
        $params = null;
        $db = null;
        $this->tools = new Tools($config, $params, $db);
    }
    
    protected function _after()
    {
        $this->tools = null;
    }
    
    // tests
    public function isIntDataProvider()
    {
        return [
            //signed int
            ['0', false, true], 
            ['1', false, true], 
            ['-1', false, true], 
            [(string) PHP_INT_MAX * -1 - 1, false, true], 
            [(string) PHP_INT_MAX, false, true], 
            
            //not signed int
            [(string) PHP_INT_MAX + 1, false, false], 
            [(string) PHP_INT_MAX * -1 - 2, false, false], 
            ['1.0', false, false], 
            ['1a', false, false], 
            ['a', false, false], 
            ['', false, false], 
            
            //unsigned int
            ['0', true, true], 
            ['1', true, true], 
            [(string) PHP_INT_MAX, true, true], 
            
            //not unsigned int
            ['-1', true, false], 
            [(string) PHP_INT_MAX * -1 - 1, true, false], 
            ['1.0', true, false], //'1.0' -> not int, but 1.0 -> int!
            ['1a', true, false], 
            ['a', true, false], 
            ['', true, false], 
            ['-0', true, false], 
        ];
    }   
    
    /**
     * @dataProvider isIntDataProvider
     */
    public function testIsInt($val, $unsigned, $expected)
    {
        $result = $this->tools->isInt($val, $unsigned);
        $this->assertEquals($expected, $result);
    }
    
    public function isArrayOfIntegersDataProvider()
    {
        return [
            [[0, 1, 2], true], 
            [[-1, 0, 1], true], 
            [[0, -0], true], 
            [[0, 1.0, 2], true], //'1.0' -> not int, but 1.0 -> int!
            [[0, '1', 2], true], 
            [[0, PHP_INT_MAX], true], 
            [[PHP_INT_MAX * -1 - 1, PHP_INT_MAX], true], 
            
            [[0, 1.1, 2], false], 
            [[0, '1a', 2], false], 
            [[0, PHP_INT_MAX + 1], false], 
            [[PHP_INT_MAX * -1 - 2, PHP_INT_MAX], false], 
        ];
    }
    
    /**
     * @dataProvider isArrayOfIntegersDataProvider
     */
    public function testIsArrayOfIntegers($val, $expected)
    {
        $result = $this->tools->isArrayOfIntegers($val);
        $this->assertEquals($expected, $result);
    }
    
    public function getArrayOfIntegersDataProvider()
    {
        return [
            [['0', '1', '2'], [0, 1, 2]], 
            [['-0', '-1', '-2'], [0, -1, -2]], 
            [[-0, -1, -2], [0, -1, -2]], 
            [['0', 1, '2'], [0, 1, 2]], 
            [['0', 1.0, '2'], [0, 1, 2]], 
            [['0', 1.1, '2'], [0, 1, 2]], 
            [['0', '1a', '2'], [0, 1, 2]], 
            [['0', 'a', '2'], [0, 0, 2]], 
            [['0', '', '2'], [0, 0, 2]], 
            [['0', ' 1', '2'], [0, 1, 2]], 
            [['0', ' 1 ', '2'], [0, 1, 2]], 
            [[0, PHP_INT_MAX], [0, PHP_INT_MAX]], 
            [[0, PHP_INT_MAX + 1], [0, PHP_INT_MAX]], // (string) (PHP_INT_MAX + 1) -> PHP_INT_MAX
            [[0, PHP_INT_MAX * -1 - 1], [0, PHP_INT_MAX * -1 - 1]], 
            [[0, PHP_INT_MAX * -1 - 2], [0, PHP_INT_MAX * -1 - 1]], //(string) (PHP_INT_MAX * -1 - 2) -> PHP_INT_MAX * -1 - 1
        ];
    }
    
    /**
     * @dataProvider getArrayOfIntegersDataProvider
     */
    public function testGetArrayOfIntegers($val, $expected)
    {
        $arr = $this->tools->getArrayOfIntegers($val);
        $this->assertTrue($arr == $expected);
    }
    
    public function isStringOfIntegersDataProvider()
    {
        return [
            ['1,2,3', ',', true], 
            ['0, 1, 2', ',', true], 
            ['-1, 0, 1', ',', true], 
            [(PHP_INT_MAX * -1 - 1) . ', 0, ' . PHP_INT_MAX, ',', true], 
            ['1 2 3', ' ', true], 
            
            ['1,2a,3', ',', false], 
            ['1.0, 2, 3', ',', false], 
            [(PHP_INT_MAX * -1 - 2) . ', 0, 1', ',', false], 
            ['-1, 0, ' . (PHP_INT_MAX + 1), ',', false], 
            ['1; 2; 3', ',', false], 
        ];
    }
    
    /**
     * @dataProvider isStringOfIntegersDataProvider
     */
    public function testIsStringOfIntegers($val, $sep, $expected)
    {
        $result = $this->tools->isStringOfIntegers($val, $sep);
        $this->assertEquals($expected, $result);
    }
    
    public function getArrayOfIntegersFromStringDataProvider()
    {
        return [
            ['0, 1, 2', ',', [0, 1, 2]], 
            ['-1, 0, 1', ',', [-1, 0, 1]], 
            ['0 1 2', ' ', [0, 1, 2]], 
            ['0, 1, 2, ' . PHP_INT_MAX, ',', [0, 1, 2, PHP_INT_MAX]], 
            [(PHP_INT_MAX * -1 - 1) . ', -1, 0, 1', ',', [(PHP_INT_MAX * -1 - 1), -1, 0, 1]], 
            
            ['0, 1.0, 2', ',', [0, 1, 2]], 
            ['0, 1.1, 2', ',', [0, 1, 2]], 
            ['0, 1a, 2', ',', [0, 1, 2]], 
            ['0, 1, 2, ' . (PHP_INT_MAX + 1), ',', [0, 1, 2, PHP_INT_MAX]], 
            [(PHP_INT_MAX * -1 - 2) . ', -1, 0, 1', ',', [(PHP_INT_MAX * -1 - 1), -1, 0, 1]], 
            ['0, a, 2', ',', [0, 0, 2]], 
            ['0, , 2', ',', [0, 0, 2]], 
            ['0,,2', ',', [0, 0, 2]], 
            ['0,true,2', ',', [0, 0, 2]], 
            ['0,null,2', ',', [0, 0, 2]], 
        ];
    }
    
    /**
     * @dataProvider getArrayOfIntegersFromStringDataProvider
     */
    public function testGetArrayOfIntegersFromString($val, $sep, $expected)
    {
        $arr = $this->tools->getArrayOfIntegersFromString($val, $sep);
        $this->assertTrue($arr == $expected);
    }
}