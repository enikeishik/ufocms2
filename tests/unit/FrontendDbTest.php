<?php
require_once __DIR__  . DIRECTORY_SEPARATOR . '_presets.php';

use \Ufocms\Frontend\Db;

class FrontendDbTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var Db
     */
    protected $db;
    
    protected function _before()
    {
        $this->db = new Db();
    }

    protected function _after()
    {
        if (null !== $this->db) {
            $this->db->close();
            $this->db = null;
        }
    }

    // tests
    public function testQuery()
    {
        $this->assertNotFalse($this->db->query('SHOW TABLES'));
    }
    
    public function testGetItem()
    {
        $item = $this->db->getItem('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=1');
        $this->assertNotNull($item);
        $this->assertTrue(array_key_exists('id', $item));
        $this->assertTrue(1 == $item['id']);
        
        $item = $this->db->getItem('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=0');
        $this->assertNull($item);
    }
    
    public function testGetValue()
    {
        $value = $this->db->getValue('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=1', 'id');
        $this->assertNotNull($value);
        $this->assertTrue(1 == $value);
        
        $this->assertNull($this->db->getValue('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=0', 'id'));
        $this->assertNull($this->db->getValue('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` LIMIT 1', 'id2'));
    }
    
    public function testGetValues()
    {
        $values = $this->db->getValues('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=1', 'id');
        $this->assertNotNull($values);
        $this->assertTrue(is_array($values));
        $this->assertTrue(1 == count($values));
        $this->assertTrue(1 == $values[0]);
        
        $values = $this->db->getValues('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=1', 'id', 'id');
        $this->assertNotNull($values);
        $this->assertTrue(is_array($values));
        $this->assertTrue(1 == count($values));
        $this->assertTrue(array_key_exists('id1', $values));
        $this->assertTrue(1 == $values['id1']);
        
        $values = $this->db->getValues('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=0', 'id');
        $this->assertNotNull($values);
        $this->assertTrue(is_array($values));
        $this->assertTrue(0 == count($values));
        
        $error = '';
        try {
            $this->db->getValues('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` LIMIT 1', 'id2');
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        $this->assertTrue('Undefined index: id2' == $error);
    }
    
    public function testGetItems()
    {
        $items = $this->db->getItems('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=1');
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertTrue(1 == count($items));
        $this->assertTrue(is_array($items[0]));
        $this->assertTrue(array_key_exists('id', $items[0]));
        $this->assertTrue(1 == $items[0]['id']);
        
        $items = $this->db->getItems('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=1', 'id');
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertTrue(1 == count($items));
        $this->assertTrue(array_key_exists('id1', $items));
        $this->assertTrue(is_array($items['id1']));
        $this->assertTrue(array_key_exists('id', $items['id1']));
        $this->assertTrue(1 == $items['id1']['id']);
        
        $items = $this->db->getItems('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` WHERE `id`=0', 'id');
        $this->assertNotNull($items);
        $this->assertTrue(is_array($items));
        $this->assertTrue(0 == count($items));
        
        $error = '';
        try {
            $this->db->getItems('SELECT `id` FROM `' . C_DB_TABLE_PREFIX . 'mainpage` LIMIT 1', 'id2');
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        $this->assertTrue('Undefined index: id2' == $error);
    }
    
    public function testGetLastInsertedId()
    {
        $this->assertTrue(0 == $this->db->getLastInsertedId());
    }
    
    public function testAddEscape()
    {
        $this->assertTrue('' == $this->db->addEscape(''));
        $this->assertTrue('123' == $this->db->addEscape('123'));
        $this->assertTrue('qwe' == $this->db->addEscape('qwe'));
        $this->assertTrue('123 qwe ,.?!<>/|[]{}()-=_+~`;:@#$%^&*' == $this->db->addEscape('123 qwe ,.?!<>/|[]{}()-=_+~`;:@#$%^&*'));
        $this->assertTrue("\t" == $this->db->addEscape("\t"));
        $this->assertTrue('\\r\\n\\0\\Z' == $this->db->addEscape("\r\n\0\x1A"));
        $this->assertTrue('\\\\' == $this->db->addEscape('\\'));
        $this->assertTrue("\'" == $this->db->addEscape("'"));
        $this->assertTrue('\"' == $this->db->addEscape('"'));
    }
    
    public function testGetError()
    {
        $this->assertTrue('' == $this->db->getError());
    }
}
