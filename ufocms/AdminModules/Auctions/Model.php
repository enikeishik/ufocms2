<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Auctions;

/**
 * Module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    protected function init()
    {
        parent::init();
        $this->defaultSort = 'DateCreate DESC';
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,                           'Title' => 'id',                    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => $this->params->sectionId,    'Title' => 'Раздел',                'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getSections'),
            array('Type' => 'bool',         'Name' => 'IsDisabled',     'Value' => false,                       'Title' => 'Отключен',              'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Отключен'), array('Value' => 0, 'Title' => 'Включен'))),
            array('Type' => 'bool',         'Name' => 'IsClosed',       'Value' => false,                       'Title' => 'Завершен',              'Filter' => true,   'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Завершен'), array('Value' => 0, 'Title' => 'Открыт'))),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => date('Y-m-d H:i:s'),         'Title' => 'Создано',               'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DatePublicate',  'Value' => date('Y-m-d H:i:s'),         'Title' => 'Опубликовано',          'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateStart',      'Value' => $this->getDateStart(),       'Title' => 'Начало',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateStop',       'Value' => $this->getDateStop(),        'Title' => 'Окончание',             'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateView',       'Value' => '1970-01-01 00:00:00',       'Title' => 'Просмотрено',           'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'int',          'Name' => 'Step',           'Value' => -100,                        'Title' => 'Шаг, руб.',             'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'int',          'Name' => 'StepTime',       'Value' => 600,                         'Title' => 'Время шага, сек.',      'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'int',          'Name' => 'PriceStart',     'Value' => 1000,                        'Title' => 'Начал. цена',           'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'int',          'Name' => 'PriceStop',      'Value' => 100,                         'Title' => 'Конечная цена',         'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'int',          'Name' => 'PriceCurrent',   'Value' => 1000,                        'Title' => 'Текущая цена',          'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'ViewedCnt',      'Value' => 0,                           'Title' => 'Просмотры',             'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                          'Title' => 'Заголовок',             'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'image',        'Name' => 'Thumbnail',      'Value' => '',                          'Title' => 'Картинка мал.',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'image',        'Name' => 'Image',          'Value' => '',                          'Title' => 'Картинка бол.',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'ShortDesc',      'Value' => '',                          'Title' => 'Кратк. текст',          'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'FullDesc',       'Value' => '',                          'Title' => 'Полн. текст',           'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
        );
    }
    
    /**
     * @param int $auctionId
     * @return array[string FirstDate, string LastDate, int Cnt, int LastUserId]|null
     */
    public function getAuctionResults($auctionId)
    {
        $sql =  'SELECT MIN(DateCreate) AS FirstDate, MAX(DateCreate) AS LastDate, COUNT(*) AS Cnt,' . 
                ' (SELECT UserId' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'auctions_log' . 
                    ' WHERE AuctionId=' . $auctionId . 
                    ' AND DateCreate=(' . 
                        'SELECT MAX(DateCreate)' . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'auctions_log' . 
                        ' WHERE AuctionId=' . $auctionId . 
                    ')' . 
                ') AS LastUserId' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'auctions_log' . 
                ' WHERE AuctionId=' . $auctionId;
        return $this->db->getItem($sql);
    }
    
    /**
     * @return string
     */
    protected function getDateStart()
    {
        return date('Y-m-d H:i:s', time() + 3600);
    }
    
    /**
     * @return string
     */
    protected function getDateStop()
    {
        return date('Y-m-d H:i:s', time() + (2 * 3600));
    }
    
    protected function setItems()
    {
        parent::setItems();
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'];
        }
        unset($item);
    }
    
    protected function collectFormData(array $fields, $update = false)
    {
        $data = parent::collectFormData($fields, $update);
        if (0 == $this->params->itemId) {
            $data['PriceCurrent'] = array('Type' => 'int', 'Value' => $data['PriceStart']['Value']);
        }
        return $data;
    }
}
