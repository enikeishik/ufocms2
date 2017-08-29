<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\News2;

/**
 * News module model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * @var Tags
     */
    protected $tags = null;
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'news2';
        $this->itemDisabledField = 'IsHidden';
        $this->defaultSort = 'DateCreate DESC';
    }
    
    protected function setItems()
    {
        $section = $this->core->getSection();
        $sectionPath = $section['path'];
        unset($section);
        parent::setItems();
        foreach ($this->items as &$item) {
            $item['path'] = $sectionPath . $item['Id'] . '/';
        }
        unset($item);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,                           'Title' => 'id',            'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'UserId',         'Value' => 0,                           'Title' => 'userId',        'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'SectionId',      'Value' => $this->params->sectionId,    'Title' => 'Раздел',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,     'Items' => 'getSections'),
            array('Type' => 'datetime',     'Name' => 'DateCreate',     'Value' => date('Y-m-d H:i:s'),         'Title' => 'Дата создания', 'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Class' => 'small'),
            array('Type' => 'datetime',     'Name' => 'DateView',       'Value' => '',                          'Title' => 'Дата просм.',   'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false,    'Class' => 'small'),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',                          'Title' => 'Заголовок',     'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'combo',        'Name' => 'Author',         'Value' => '',                          'Title' => 'Автор',         'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => 'getAuthors'),
            array('Type' => 'image',        'Name' => 'Icon',           'Value' => '',                          'Title' => 'Картинка',      'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'image',        'Name' => 'InsIcon',        'Value' => '',                          'Title' => 'Картинка вст.', 'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'Announce',       'Value' => '',                          'Title' => 'Анонс',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Body',           'Value' => '',                          'Title' => 'Текст',         'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'int',          'Name' => 'ViewedCnt',      'Value' => 0,                           'Title' => 'Просмотров',    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'bool',         'Name' => 'IsHidden',       'Value' => false,                       'Title' => 'Скрыто',        'Filter' => true,   'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => array(array('Value' => 1, 'Title' => 'Скрыто'), array('Value' => 0, 'Title' => 'Открыто'))),
            array('Type' => 'bool',         'Name' => 'IsRss',          'Value' => true,                        'Title' => 'RSS',           'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            /* deprecated array('Type' => 'bool',         'Name' => 'IsTimered',      'Value' => false,                       'Title' => 'Отсрочка',      'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true), */
            array('Type' => 'bool',         'Name' => 'IsDisabled',     'Value' => false,                       'Title' => 'Откл.полз.',    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
        );
    }
    
    /**
     * @return array
     */
    protected function getAuthors()
    {
        static $authors = null;
        if (null === $authors) {
            $sql =  'SELECT DISTINCT Author' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'news2' . 
                    ' ORDER BY Author';
            $authors = $this->db->getItems($sql);
            foreach ($authors as &$item) {
                $item = array('Value' => $item['Author'], 'Title' => $item['Author']);
            }
            unset($item);
        }
        return $authors;
    }
    
    /**
     * @return array
     */
    public function getAnotherSections()
    {
        $sql = 'SELECT AnotherSectionId' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'news2_ns' . 
               ' WHERE ItemId=' . $this->params->itemId;
        return $this->db->getValues($sql, 'AnotherSectionId');
    }
    
    protected function initTags()
    {
        if (is_null($this->tags)) {
            $this->tags = new Tags($this->db);
        }
    }
    
    /**
     * @return array
     */
    public function getTags()
    {
        $this->initTags();
        return $this->tags->get();
    }
    
    /**
     * @return array
     */
    public function getItemTags()
    {
        $this->initTags();
        return $this->tags->getItemTags($this->params->itemId);
    }
    
    /**
     * @param int $itemId
     * @param array $relations
     * @param bool $new = false
     */
    protected function setRelations($itemId, $relations, $new = false)
    {
        if (is_array($relations)) {
            $relations_ = array();
            foreach ($relations as $relation) {
                if ($relation == (int) $relation) {
                    $relations_[] = $relation;
                }
            }
            $relations = $relations_;
        } else {
            $relations = array();
        }
        $relationsDb = array();
        if (!$new) {
            //проверяем удаленные связи
            $sql = 'SELECT Id,AnotherSectionId FROM ' . C_DB_TABLE_PREFIX . 'news2_ns' . 
                   ' WHERE ItemId=' . $itemId;
            $items = $this->db->getItems($sql);
            foreach ($items as $item) {
                //заносим связи базы в массив, для обратной проверки
                $relationsDb[] = $item['AnotherSectionId'];
                $relationExists = false;
                foreach ($relations as $relId) {
                    if (0 != $relId && $relId == $item['AnotherSectionId']) {
                        $relationExists = true;
                        break;
                    }
                }
                if (!$relationExists) {
                    //если в базе связь есть, но в массиве ее нет, удаляем связь из базы
                    $sql = 'DELETE FROM ' . C_DB_TABLE_PREFIX . 'news2_ns' . 
                           ' WHERE Id=' . $item['Id'];
                    $this->db->query($sql);
                }
            }
        }
        
        //проверяем добавленные связи
        foreach ($relations as $relId) {
            $relationExists = false;
            foreach ($relationsDb as $relDbId) {
                if (0 != $relId && $relId == $relDbId) {
                    $relationExists = true;
                    break;
                }
            }
            if (!$relationExists && $relId != $this->params->sectionId) {
                //если в базе связи нет, но в массиве она есть, добавляем связь в базу
                //WARNING: здесь мы не делаем проверку на существование $relId в БД
                //но при добавлении записей через сайт зарегистрированными пользователями сайта
                //такая проверка необходима, также проверка необходима и для $sectionId
                $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'news2_ns' . 
                       ' (SectionId,ItemId,AnotherSectionId)' . 
                       ' VALUES(' . $this->params->sectionId .  ',' . $itemId . ',' . $relId . ')';
                $this->db->query($sql);
            }
        }
    }
    
    /**
     * @param int $itemId
     * @param array $tags
     * @param string $newTags
     */
    protected function setTags($itemId, $tags, $newTags)
    {
        if (is_array($tags)) {
            $tags_ = array();
            foreach ($tags as $tagId) {
                if ($tagId == (int) $tagId) {
                    $tags_[] = $tagId;
                }
            }
            $tags = $tags_;
        } else {
            $tags = array();
        }
        $newTags = preg_replace('/ {2,}/', ' ', preg_replace('/[^A-Za-zА-Яа-яЁё0-9\-\r\n]+/u', ' ', $newTags));
        
        $sql = 'SELECT TagId' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'news2_nt' . 
               ' WHERE ItemId=' . $itemId;
        $tagsDb = $this->db->getValues($sql, 'TagId');
        
        $this->initTags();
        
        if (!is_null($tagsDb)) {
            //проверяем не отключили ли какие-либо из привязанных тэгов
            $tagsRemoved = array();
            foreach ($tagsDb as $tagDbId) {
                $tagRemoved = true;
                foreach ($tags as $tagId) {
                    if ($tagId == $tagDbId) {
                        $tagRemoved = false;
                        break;
                    }
                }
                if ($tagRemoved) {
                    $tagsRemoved[] = $tagDbId;
                }
            }
            //удаляем привязки отключенных тэгов
            if (0 < count($tagsRemoved)) {
                $this->tags->unbind($itemId, $tagsRemoved);
            }
        }
        
        if (0 < count($tags)) {
            //проверяем не подключили ли какие-либо новые тэги
            $tagsBinded = array();
            foreach ($tags as $tagId) {
                $tagBinded = true;
                foreach ($tagsDb as $tagDbId) {
                    if ($tagId == $tagDbId) {
                        $tagBinded = false;
                        break;
                    }
                }
                if ($tagBinded) {
                    $tagsBinded[] = $tagId;
                }
            }
            //добавляем привязки подключенных тэгов
            if (0 < count($tagsBinded)) {
                $this->tags->bind($itemId, $tagsBinded);
            }
        }
        unset($tagsDb);
        
        //обрабатываем новые тэги, введенные вручную
        if ('' != $newTags) {
            if (false !== strpos($newTags, "\r\n")) {
                $tagsNew_ = explode("\r\n", $newTags);
            } else if (false !== strpos($newTags, "\n")) {
                $tagsNew_ = explode("\n", $newTags);
            } else if (false !== strpos($newTags, "\r")) {
                $tagsNew_ = explode("\r", $newTags);
            } else {
                $tagsNew_[] = $newTags;
            }
            $tagsNew = array();
            foreach ($tagsNew_ as $tag) {
                $tagsNew[] = trim($tag);
            }
            $tagsNew_ = $tagsNew;
            
            //проверяем не существуют ли уже введенные тэги
            $tagsNew = array();
            $tagsDb = array();
            foreach ($tagsNew_ as $tag) {
                if ('' != $tag) {
                    $tagId = $this->tags->getId($tag);
                    if (is_null($tagId)) {
                        $tagsNew[] = $tag;
                    } else {
                        $tagsDb[] = $tagId;
                    }
                }
            }
            unset($tagsNew_);
            
            if (0 < count($tagsDb)) {
                //привязываем к уже существующим тэгам
                $this->tags->bind($itemId, $tagsDb);
            }
            unset($tagsDb);
            
            //добавляем новые тэги в БД
            $tagsNewIds = array();
            foreach ($tagsNew as $tag) {
                $tagId = $this->tags->add($tag);
                if (!is_null($tagId)) {
                    $tagsNewIds[] = $tagId;
                }
            }
            unset($tagsNew);
            
            if (0 < count($tagsNewIds)) {
                //создаем привязки
                $this->tags->bind($itemId, $tagsNewIds);
            }
            unset($tagsNewIds);
        }
    }
    
    /**
     * @see parent
     */
    protected function actionAfterInsert()
    {
        $anotherSections = isset($_POST['anothersections']) ?   $_POST['anothersections']     : array();
        $tags            = isset($_POST['tags'])            ?   $_POST['tags']                : array();
        $newTags         = isset($_POST['newtags'])         ?   (string) $_POST['newtags']    : '';
        $this->setRelations($this->lastInsertedId, $anotherSections, true);
        $this->setTags($this->lastInsertedId, $tags, $newTags);
    }
    
    /**
     * @see parent
     */
    protected function actionAfterUpdate()
    {
        $anotherSections = isset($_POST['anothersections']) ?   $_POST['anothersections']     : array();
        $tags            = isset($_POST['tags'])            ?   $_POST['tags']                : array();
        $newTags         = isset($_POST['newtags'])         ?   (string) $_POST['newtags']    : '';
        $this->setRelations($this->params->itemId, $anotherSections, false);
        $this->setTags($this->params->itemId, $tags, $newTags);
    }
    
    /**
     * @see parent
     */
    protected function actionAfterDelete()
    {
        $sql = 'DELETE FROM ' . C_DB_TABLE_PREFIX . 'news2_ns WHERE ItemId=' . $this->params->itemId;
        $ret1 = $this->db->query($sql);
        $sql = 'DELETE FROM ' . C_DB_TABLE_PREFIX . 'news2_nt WHERE ItemId=' . $this->params->itemId;
        $ret2 = $this->db->query($sql);
        return $ret1 && $ret2;
    }
}
