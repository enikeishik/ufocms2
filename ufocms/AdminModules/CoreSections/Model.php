<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreSections;

/**
 * Core sections model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    const MASK_CPL = 4;
    
    const NESTING_LIMIT = 10;
    
    const SIBLINGS_LIMIT = 1000;
    
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'sections';
        $this->itemIdField = 'id';
        $this->itemDisabledField = 'isenabled';
        $this->itemDisabledFieldInvert = true;
        $this->primaryFilter = '';
        $this->defaultSort = 'mask';
        $this->config->registerAction('up');
        $this->config->registerMakeAction('up');
        $this->config->registerAction('down');
        $this->config->registerMakeAction('down');
        $this->params->actionUnsafe = false;
    }
    
    protected function setItems()
    {
        $sql =  'SELECT *, id AS itemid, (levelid + 1) AS level, NOT isenabled AS disabled' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' ORDER BY mask';
        $this->items = $this->db->getItems($sql);
        $this->itemsCount = count($this->items);
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'id',             'Value' => 0,       'Title' => 'id',                    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'topid',          'Value' => 0,       'Title' => 'Разд.верхн.уровня',     'Filter' => true,   'Show' => false,    'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'parentid',       'Value' => 0,       'Title' => 'Родит.раздел',          'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Unchange' => true,     'Required' => true,     'Items' => 'getSections'),
            array('Type' => 'int',          'Name' => 'orderid',        'Value' => 0,       'Title' => 'Порядок вывода',        'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => false),
            array('Type' => 'int',          'Name' => 'levelid',        'Value' => 0,       'Title' => 'Уровень влож.',         'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => false),
            array('Type' => 'bool',         'Name' => 'isparent',       'Value' => false,   'Title' => 'Есть подразделы',       'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => false),
            array('Type' => 'list',         'Name' => 'moduleid',       'Value' => 0,       'Title' => 'Модуль',                'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Unchange' => true,     'Items' => 'getModules'),
            array('Type' => 'int',          'Name' => 'designid',       'Value' => 0,       'Title' => 'Ид. дизайна',           'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'text',         'Name' => 'mask',           'Value' => '',      'Title' => 'Маска',                 'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => false),
            array('Type' => 'path',         'Name' => 'path',           'Value' => '',      'Title' => 'Путь',                  'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,                             'Info' => 'Путь (URL) раздела может содержать только символны латинского алфавита, цифры и дефис'),
            array('Type' => 'image',        'Name' => 'image',          'Value' => '',      'Title' => 'Картинка страницы',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'image',        'Name' => 'timage',         'Value' => '',      'Title' => 'Картинка заголовка',    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'text',         'Name' => 'indic',          'Value' => '',      'Title' => 'Название',              'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true,    'Info' => 'Краткое название, используется в меню'),
            array('Type' => 'text',         'Name' => 'title',          'Value' => '',      'Title' => 'Заголовок',             'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Required' => true,                             'Info' => 'Заголовок, используется в тэге TITLE и на странице в тэге H1', 'Raw' => true),
            array('Type' => 'text',         'Name' => 'metadesc',       'Value' => '',      'Title' => 'Описание',              'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,                                                     'Info' => 'Описание, мета тэг DESCRIPTION'),
            array('Type' => 'text',         'Name' => 'metakeys',       'Value' => '',      'Title' => 'Ключевые слова',        'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,                                                     'Info' => 'Ключевые слова, мета тэг KEYWORDS'),
            array('Type' => 'bool',         'Name' => 'isenabled',      'Value' => true,    'Title' => 'Включен',               'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true,     'Required' => true),
            array('Type' => 'bool',         'Name' => 'insearch',       'Value' => true,    'Title' => 'В поиске',              'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'inmenu',         'Value' => true,    'Title' => 'В меню',                'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'inlinks',        'Value' => true,    'Title' => 'В ссылках',             'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'inmap',          'Value' => true,    'Title' => 'На карте сайта',        'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'shtitle',        'Value' => true,    'Title' => 'Отобр.заголовок',       'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'shmenu',         'Value' => true,    'Title' => 'Отобр.меню',            'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'shlinks',        'Value' => true,    'Title' => 'Отобр.ссылки',          'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'shcomments',     'Value' => false,   'Title' => 'Вкл.комментарии',       'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'shrating',       'Value' => false,   'Title' => 'Вкл.рейтинг',           'Filter' => false,  'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'int',          'Name' => 'flsearch',       'Value' => 0,       'Title' => 'Группа поиска',         'Filter' => true,   'Show' => false,    'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'flcache',        'Value' => true,    'Title' => 'Кэшировать',            'Filter' => true,   'Show' => false,    'Sort' => true,     'Edit' => true),
        );
    }
    
    public function getSections($nc = false)
    {
        $sections = array(array('Value' => 0, 'Title' => 'Главная страница'));
        $items = $this->core->getSections();
        foreach ($items as $item) {
            $sections[] = array('Value' => $item['id'], 
                                'Title' => str_pad('', ($item['levelid'] + 1) * 4, '.', STR_PAD_LEFT) . $item['indic']);
        }
        unset($item);
        unset($items);
        return $sections;
    }
    
    /**
     * @return array
     */
    protected function getModules()
    {
        static $modules = null;
        if (null === $modules) {
            $sql =  'SELECT muid AS Value, mname AS Title' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'modules' . 
                    ' WHERE isenabled!=0 AND muid>1' . 
                    ' ORDER BY mname';
            $items = $this->db->getItems($sql);
            if (null === $items) {
                $items = array();
            }
            $modules = array_merge(array(array('Value' => 1, 'Title' => 'Документы')), $items);
        }
        return $modules;
    }
    
    /**
     * @param int $moduleId
     * @return string|null
     */
    public function getModuleTitle($moduleId)
    {
        if (-1 == $moduleId) {
            return 'Главная страница';
        }
        $modules = $this->getModules();
        foreach ($modules as $module) {
            if ($module['Value'] == $moduleId) {
                return $module['Title'];
            }
        }
        return null;
    }
    
    /**
     * @todo: refactoring
     */
    public function update()
    {
        if (is_null($this->params->itemId)) {
            $this->result = 'Request error: param `itemid` not set';
            return;
        }
        if (is_null($this->fields)) {
            $this->setFields();
        }
        if (0 == $this->params->itemId) {
            $this->add();
            return;
        }
        
        $fields = '';
        foreach ($this->fields as $field) {
            if (!$field['Edit'] || (isset($field['Unchange']) && $field['Unchange'])) {
                continue;
            }
            if (!isset($_POST[$field['Name']]) && 'bool' != $field['Type']) {
                $this->result = 'Request error: field `' . $field['Name'] . '` not set';
                return;
            }
            switch ($field['Type']) {
                case 'int':
                case 'list':
                    $fields .= '`' . $field['Name'] . '`=' . (int) $_POST[$field['Name']] . ',';
                    break;
                case 'bool':
                    if (isset($_POST[$field['Name']])) {
                        $fields .= '`' . $field['Name'] . '`=' . (int) $_POST[$field['Name']] . ',';
                    } else {
                        $fields .= '`' . $field['Name'] . '`=0,';
                    }
                    break;
                default:
                    $fields .= '`' . $field['Name'] . "`='" . $this->db->addEscape($_POST[$field['Name']]) . "',";
            }
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' SET ' . substr($fields, 0, -1) . 
                ' WHERE id=' . $this->params->itemId;
        if ($this->db->query($sql)) {
            $this->result = 'updated';
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
    
    protected function add()
    {
        foreach ($this->fields as $field) {
            if ($field['Edit'] && !isset($_POST[$field['Name']]) && 'bool' != $field['Type']) {
                $this->result = 'Request error: field `' . $field['Name'] . '` not set';
                return;
            }
        }
        $parentId = (int) $_POST['parentid'];
        $topId = $this->getTopId($parentId);
        $path = $this->getSafeSectionPath($this->getParentPath($parentId) . $_POST['path'] . '/');
        if ($this->isPathExists($path)) {
            $this->result = 'Request error: path `' . $path . '` already exists';
            return;
        }
        $orderId = $this->getMaxOrder($parentId) + 1;
        $moduleId = (int) $_POST['moduleid'];
        if (!$this->isModuleExists($moduleId)) {
            $this->result = 'Request error: module with id `' . $moduleId . '` not exists';
            return;
        }
        $sql = 'INSERT INTO ' . C_DB_TABLE_PREFIX . 'sections ' .
           '(parentid,topid,orderid,levelid,moduleid,designid,mask,' . 
           'path,image,timage,indic,title,metadesc,metakeys,' .
           'isenabled,insearch,inmenu,inlinks,inmap,' . 
           'shtitle,shmenu,shlinks,shcomments,shrating,' . 
           'flsearch,flcache) ' .
           'VALUES(' . 
           $parentId . ',' . 
           $topId . ',' . 
           $orderId . ',' . 
           $this->getLevel($parentId) . ',' . 
           $moduleId . ',' . 
           (int) $_POST['designid'] . ',' . 
           "'" . $this->getMask($parentId, $orderId) . "'," . 
           "'" . $path . "'," . 
           "'" . $this->db->addEscape($_POST['image']) . "'," . 
           "'" . $this->db->addEscape($_POST['timage']) . "'," . 
           "'" . $this->db->addEscape($_POST['indic']) . "'," . 
           "'" . $this->db->addEscape($_POST['title']) . "'," . 
           "'" . $this->db->addEscape($_POST['metadesc']) . "'," . 
           "'" . $this->db->addEscape($_POST['metakeys']) . "'," .  
           (int) isset($_POST['isenabled']) . ',' . 
           (int) isset($_POST['insearch']) . ',' . 
           (int) isset($_POST['inmenu']) . ',' . 
           (int) isset($_POST['inlinks']) . ',' . 
           (int) isset($_POST['inmap']) . ',' . 
           (int) isset($_POST['shtitle']) . ',' . 
           (int) isset($_POST['shmenu']) . ',' . 
           (int) isset($_POST['shlinks']) . ',' . 
           (int) isset($_POST['shcomments']) . ',' . 
           (int) isset($_POST['shrating']) . ',' . 
           (int) $_POST['flsearch'] . ',' . 
           (int) isset($_POST['flcache']) . ')';
        if ($this->db->query($sql)) {
            $this->lastInsertedId = $this->db->getLastInsertedId();
            $err = '';
            if (0 == $topId) {
                $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'sections' . 
                        ' SET topid=' . $this->lastInsertedId . 
                        ' WHERE id=' . $this->lastInsertedId;
                if (!$this->db->query($sql)) {
                    $err .= ' ' . $this->db->getError();
                }
            }
            if (0 != $parentId) {
                $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'sections' . 
                        ' SET isparent=1' . 
                        ' WHERE id=' . $parentId;
                if (!$this->db->query($sql)) {
                    $err .= ' ' . $this->db->getError();
                }
            }
            if ('' == $err) {
                if ($this->addContent($this->lastInsertedId, $moduleId)) {
                    $this->result = 'updated';
                } else {
                    $this->result = 'DB error: ' . $this->db->getError();
                }
            } else {
                $this->result = 'DB error(s): ' . $err;
            }
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
    
    /**
     * Получение информации об элементе.
     * @param int $itemId
     * @param array|string $fields = null
     */
    protected function getItemInfo($itemId, $fields = null)
    {
        if (is_null($fields)) {
            $fields = '*';
        } else if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        $sql =  'SELECT ' . $fields . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE id=' . $itemId;
        return $this->db->getItem($sql);
    }
    
    protected function getParentPath($parentId)
    {
        if (0 == $parentId) {
            return '';
        }
        $sql =  'SELECT path' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE id=' . $parentId;
        $val = $this->db->getValue($sql, 'path');
        return !is_null($val) ? $val : '';
    }
    
    protected function getSafeSectionPath($path)
    {
        $out = preg_replace('/\/+/',
                            '/',
                            preg_replace('/[^a-z0-9~_\-\/]/',
                                         '',
                                         strtolower($path)));
        if ($out{0} != '/') {
            $out = '/' . $out;
        }
        if ($out{strlen($out) - 1} != '/') {
            $out .= '/';
        }
        return $out;
    }
    
    protected function isPathExists($path)
    {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' .
                " WHERE path='" . $path . "'";
        $val = $this->db->getValue($sql, 'Cnt');
        return !is_null($val) ? 0 != $val : false;
    }
    
    protected function getTopId($parentId)
    {
        if (0 == $parentId) {
            return 0;
        }
        $sql =  'SELECT topid' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE id=' . $parentId;
        $val = $this->db->getValue($sql, 'topid');
        return !is_null($val) ? $val : 0;
    }
    
    protected function getMaxOrder($parentId)
    {
        $sql =  'SELECT MAX(orderid) AS maxorderid' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE parentid=' . $parentId;
        $val = $this->db->getValue($sql, 'maxorderid');
        return !is_null($val) ? $val : 0;
    }
    
    protected function getLevel($parentId)
    {
        if (0 == $parentId) {
            return 0;
        }
        $sql = 'SELECT levelid' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE id=' . $parentId;
        $val = $this->db->getValue($sql, 'levelid');
        return !is_null($val) ? ++$val : 0;
    }
    
    protected function getMask($parentId, $orderId)
    {
        $mask = $this->getLocalMask($orderId);
        if (0 != $parentId) {
            $sql =  'SELECT mask' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                    ' WHERE id=' . $parentId;
            $parentMask = $this->db->getValue($sql, 'mask');
            if (!is_null($parentMask)) {
                $mask = $parentMask . $mask;
            }
        }
        return $mask;
    }
    
    /**
     * @todo: replace with str_pad
     */
    protected function getLocalMask($orderId)
    {
        $mask = (string) $orderId;
        for ($i = strlen($mask); $i < self::MASK_CPL; $i++) {
            $mask = '0' . $mask;
        }
        return $mask;
    }
    
    protected function isModuleExists($moduleId)
    {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'modules' .
                ' WHERE muid=' . $moduleId;
        $val = $this->db->getValue($sql, 'Cnt');
        return !is_null($val) ? 0 != $val : false;
    }
    
    protected function addContent($sectionId, $moduleId)
    {
        $sql = 'SELECT mtable FROM ' . C_DB_TABLE_PREFIX . 'modules WHERE muid=' . $moduleId;
        if ($module = $this->db->getItem($sql)) {
            $sql = 'INSERT INTO `' . C_DB_TABLE_PREFIX . $module['mtable'] . '` (sectionid) VALUES(' . $sectionId . ')';
            return $this->db->query($sql);
        } else {
            return false;
        }
    }
    
    public function delete()
    {
        if (is_null($this->params->itemId)) {
            $this->result = 'Request error';
            return;
        }
        if (0 >= $this->params->itemId) {
            $this->result = 'Section is system and can not be deleted';
            return;
        }
        if ($this->isParent($this->params->itemId)) {
            $this->result = 'Section is parent and can not be deleted';
            return;
        }
        $sql = 'SELECT parentid FROM ' . C_DB_TABLE_PREFIX . 'sections WHERE id=' . $this->params->itemId;
        $parendId = $this->db->getValue($sql, 'parentid');
        if (is_null($parendId)) {
            $this->result = 'Section with id `' . $this->params->itemId . '` can not be found';
            return;
        }
        
        $module = $this->getSectionModuleTables($this->params->itemId); //before deleting
        if (!is_array($module)) {
            $this->result = 'Error: section module not found';
            return;
        }
        
        $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE id=' . $this->params->itemId;
        if ($this->db->query($sql)) {
            $err = '';
            if (!$this->reorder($parendId)) {
                $err .= ' ' . $this->db->getError();
            }
            if (!$this->resetMask('', $parendId)) {
                $err .= ' ' . $this->db->getError();
            }
            if (!$this->setParentFlag($parendId)) {
                $err .= ' ' . $this->db->getError();
            }
            if (!$this->deleteContent($module['mtable'], $module['mtableitems'])) {
                $err .= ' ' . $this->db->getError();
            }
            if ('' == $err) {
                $this->result = 'deleted';
            } else {
                $this->result = 'DB error(s): ' . $err;
            }
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
    
    protected function isParent($sectionId)
    {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' .
                ' WHERE parentid=' . $sectionId;
        $val = $this->db->getValue($sql, 'Cnt');
        return !is_null($val) ? 0 != $val : false;
    }
    
    protected function reorder($parentId)
    {
        $sql =  'SELECT id FROM ' . C_DB_TABLE_PREFIX . 'sections' .
                ' WHERE parentid=' . $parentId .
                ' ORDER BY orderid';
        $items = $this->db->getItems($sql);
        if (!is_null($items)) {
            $order = 1;
            foreach ($items as $item) {
                $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'sections' . 
                        ' SET orderid=' . $order++ .
                        ' WHERE id=' . $item['id'];
                $this->db->query($sql);
            }
            return true;
        } else {
            return true;
        }
    }
    
    protected function resetMask($parentMask, $parentId)
    {
        $sql =  'SELECT id,orderid,isparent' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' .
                ' WHERE parentid=' . $parentId;
        $items = $this->db->getItems($sql);
        if (!is_null($items)) {
            foreach ($items as $item) {
                $mask = $this->getLocalMask($item['orderid']);
                if ('' == $parentMask) {
                    $parentMask = $this->getMaskById($parentId);
                }
                $mask = $parentMask . $mask;
                $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'sections' . 
                        " SET mask='" . $mask . "'" . 
                        ' WHERE id=' . $item['id'];
                $this->db->query($sql);
                if ($item['isparent']) {
                    $this->resetMask($mask, $item['id']);
                }
            }
            return true;
        } else {
            return true;
        }
    }
    
    protected function getMaskById($id)
    {
        if (0 == $id) {
            return '';
        }
        $sql =  'SELECT mask' .
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE id=' . $id;
        $val = $this->db->getValue($sql, 'mask');
        return !is_null($val) ? $val : '';
    }
    
    protected function setParentFlag($id)
    {
        if (0 == $id) {
            return true;
        }
        $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' SET isparent=' . (int) $this->isParent($id) . 
                ' WHERE id=' . $id;
        return $this->db->query($sql);
    }
    
    /**
     * @param string $table
     * @param string $tableItems
     * @return 
     */
    protected function deleteContent($table, $tableItems)
    {
        if ('' != $table && '' != $tableItems && $table != $tableItems) {
            $result = true;
            $sql = 'DELETE FROM `' . C_DB_TABLE_PREFIX . $table . '` WHERE SectionId=' . $this->params->itemId;
            if (!$this->db->query($sql)) {
                $result = false;
            }
            $tables = explode(',', $tableItems);
            foreach ($tables as $tbl) {
                $sql = 'DELETE FROM `' . C_DB_TABLE_PREFIX . $tbl . '` WHERE SectionId=' . $this->params->itemId;
                if (!$this->db->query($sql)) {
                    $result = false;
                }
            }
            return $result;
        } else if (0 < strlen($table)) {
            $sql = 'DELETE FROM `' . C_DB_TABLE_PREFIX . $table . '` WHERE sectionid=' . $this->params->itemId;
            return $this->db->query($sql);
        }
    }
    
    /**
     * @param int $sectionId
     * @return array|null
     */
    protected function getSectionModuleTables($sectionId)
    {
        $sql =  'SELECT m.mtable, m.mtableitems' .
                ' FROM ' . C_DB_TABLE_PREFIX . 'modules AS m' . 
                ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s' .
                ' ON m.muid=s.moduleid' .
                ' WHERE s.id=' . $sectionId;
        return $this->db->getItem($sql);
    }
    
    /**
     * @param int $itemId
     * @param int $changerId
     * @param int $itemOrder
     * @param int $changerOrder
     * @return bool
     */
    protected function swapOrder($itemId, $changerId, $itemOrder, $changerOrder)
    {
        $sql1 = 'UPDATE ' . C_DB_TABLE_PREFIX . 'sections SET orderid=' . $changerOrder . ' WHERE id=' . $itemId;
        $sql2 = 'UPDATE ' . C_DB_TABLE_PREFIX . 'sections SET orderid=' . $itemOrder . ' WHERE id=' . $changerId;
        return $this->db->query($sql1) && $this->db->query($sql2);
    }
    
    public function up()
    {
        $item = $this->getItemInfo($this->params->itemId, 'parentid, orderid');
        if (null === $item) {
            $this->result = 'Error: Item not found';
            return;
        }
        
        $itemParentId = $item['parentid'];
        $itemOrder = $item['orderid'];
        if ($itemOrder <= 1) {
            $this->result = 'Item already first';
            return;
        }
        unset($item);
        
        $sql =  'SELECT id, orderid' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' .
                ' WHERE parentid=' . $itemParentId .
                ' AND orderid=' . ($itemOrder - 1);
        if ($item = $this->db->getItem($sql)) {
            $changerId = $item['id'];
            $changerOrder = $item['orderid'];
        } else {
            $this->result = 'Error: Changer not found';
            return;
        }
        
        if ($this->swapOrder($this->params->itemId, $changerId, $itemOrder, $changerOrder)) {
            $this->result = 'updated';
            $this->resetMask('', $itemParentId);
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
    
    public function down()
    {
        $item = $this->getItemInfo($this->params->itemId, 'parentid, orderid');
        if (null === $item) {
            $this->result = 'Error: Item not found';
            return;
        }
        
        $itemParentId = $item['parentid'];
        $itemOrder = $item['orderid'];
        if ($itemOrder == $this->getMaxOrder($itemParentId)) {
            $this->result = 'Item already last';
            return;
        }
        unset($item);
        
        $sql =  'SELECT id, orderid' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' .
                ' WHERE parentid=' . $itemParentId .
                ' AND orderid=' . ($itemOrder + 1);
        if ($item = $this->db->getItem($sql)) {
            $changerId = $item['id'];
            $changerOrder = $item['orderid'];
        } else {
            $this->result = 'Error: Changer not found';
            return;
        }
        
        if ($this->swapOrder($this->params->itemId, $changerId, $itemOrder, $changerOrder)) {
            $this->result = 'updated';
            $this->resetMask('', $itemParentId);
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
}
