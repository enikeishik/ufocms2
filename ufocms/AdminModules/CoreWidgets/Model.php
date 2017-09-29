<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\CoreWidgets;

/**
 * Core widgets model class
 */
class Model extends \Ufocms\AdminModules\Model
{
    /**
     * @var Widget
     */
    protected $widget = null;
    
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'widgets';
        $this->primaryFilter = '';
        $this->defaultSort = 'PlaceId, OrderId';
    }
    
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,           'Title' => 'id',                    'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => false),
            array('Type' => 'mlist',        'Name' => 'TrgSections',    'Value' => array(0),    'Title' => 'Разделы отображения',   'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Items' => 'getTrgSections',    'External' => true,     'Class' => 'small'),
            array('Type' => 'rlist',        'Name' => 'TypeId',         'Value' => 0,           'Title' => 'Тип',                   'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Items' => 'getTypes',          'Required' => true,     'Class' => 'small',     'Unchange' => true),
            array('Type' => 'combo',        'Name' => 'PlaceId',        'Value' => 0,           'Title' => 'Позиция',               'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true,     'Items' => 'getPlaces'),
            array('Type' => 'int',          'Name' => 'OrderId',        'Value' => 0,           'Title' => 'Порядок',               'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'IsDisabled',     'Value' => false,       'Title' => 'Отключен',              'Filter' => true,   'Show' => true,     'Sort' => true,     'Edit' => true),
            array('Type' => 'bool',         'Name' => 'ShowTitle',      'Value' => true,        'Title' => 'Отображать заголовок',  'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'mlist',        'Name' => 'SrcSections',    'Value' => '',          'Title' => 'Разделы-источники',     'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Items' => 'getSrcSections',    'Required' => true),
            /* array('Type' => 'mlist',        'Name' => 'SrcItems',       'Value' => '',          'Title' => 'Элементы-источники',    'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true), */
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',          'Title' => 'Заголовок',             'Filter' => false,  'Show' => true,     'Sort' => true,     'Edit' => true,     'Required' => true,     'Autofocus' => true),
            array('Type' => 'text',         'Name' => 'Description',    'Value' => '',          'Title' => 'Описание',              'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'Content',        'Value' => '',          'Title' => 'Содержимое',            'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true),
            array('Type' => 'subform',      'Name' => 'Params',         'Value' => '',          'Title' => 'Параметры',             'Filter' => false,  'Show' => false,    'Sort' => false,    'Edit' => true,     'Model' => 'getWidget'),
        );
    }
    
    public function getTrgSections()
    {
        $items = array_merge(
            array(array('id' => -1, 'levelid' => -1, 'indic' => 'Главная страница', 'isenabled' => 1)),
            $this->core->getSections('id,levelid,indic,isenabled')
        );
        foreach ($items as &$item) {
            $item = array(
                'Value'     => $item['id'], 
                'Title'     => str_pad('', ($item['levelid'] + 1) * 4, '.', STR_PAD_LEFT) . $item['indic'], 
                'IsEnabled' => $item['isenabled']
            );
        }
        unset($item);
        return $items;
    }
    
    /**
     * @param int $widgetId
     * @return array<int>
     */
    public function getLinkedTrgSectionsIds($widgetId)
    {
        static $items = null;
        if (null === $items) {
            $sql =  'SELECT WidgetId, SectionId' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'widgets_targets' . 
                    ' ORDER BY WidgetId';
            $itms = $this->db->getItems($sql);
            foreach ($itms as $itm) {
                $items[$itm['WidgetId']][] = $itm['SectionId'];
            }
            unset($itms);
        }
        if (isset($items[$widgetId])) {
            return $items[$widgetId];
        } else {
            return array();
        }
    }
    
    public function getTypes()
    {
        $sql =  'SELECT wt.Id, wt.Title, wt.Description, m.mname' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'widgets_types AS wt' . 
                ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'modules AS m ON m.muid=wt.ModuleId' . 
                ' ORDER BY wt.Title';
        $items = $this->db->getItems($sql);
        foreach ($items as &$item) {
            $item = array(
                'Value'         => $item['Id'], 
                'Title'         => $item['Title'], 
                'Description'   => $item['Description'], 
                'Module'        => $item['mname'], 
            );
        }
        unset($item);
        return $items;
    }
    
    /**
     * @return array
     */
    public function getPlaces()
    {
        $sql =  'SELECT DISTINCT PlaceId AS Value, PlaceId AS Title' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'widgets' . 
                ' ORDER BY PlaceId';
        $items = $this->db->getItems($sql);
        if (null === $items) {
            $items = array();
        }
        return $items;
    }
    
    /**
     * @return array
     */
    public function getSrcSections()
    {
        $items = $this->core->getSections('id,levelid,moduleid,indic,isenabled');
        foreach ($items as &$item) {
            $item = array(
                'Value'     => $item['id'], 
                'Title'     => str_pad('', $item['levelid'] * 4, '.', STR_PAD_LEFT) . $item['indic'], 
                'ModuleId'  => $item['moduleid'], 
                'IsEnabled' => $item['isenabled']
            );
        }
        unset($item);
        return $items;
    }
    
    /**
     * @param int $typeId
     * @return string|null
     */
    protected function getModuleName($typeId)
    {
        static $name = null;
        if (null === $name) {
            $sql =  'SELECT m.madmin' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'widgets_types AS wt' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'modules AS m ON m.muid=wt.ModuleId' . 
                    ' WHERE wt.Id=' . $typeId;
            $name = $this->db->getValue($sql, 'madmin');
            if (null !== $name) {
                $name = substr($name, 4);
            }
        }
        return $name;
    }
    
    /**
     * @param int $typeId
     * @return string|null
     */
    protected function getWidgetName($typeId)
    {
        static $name = null;
        if (null === $name) {
            $sql =  'SELECT Name' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'widgets_types' . 
                    ' WHERE Id=' . $typeId;
            $name = $this->db->getValue($sql, 'Name');
        }
        return $name;
    }
    
    protected function setWidget()
    {
        if (null !== $this->widget) {
            return;
        }
        
        $item = $this->getItem();
        if (null === $item) {
            return;
        }
        $itemId = (int) $item['Id'];
        
        //set moduleParams for source-dependent widgets
        if (0 != $itemId && !isset($this->moduleParams['SrcSections'])) {
            if (false !== strpos($item['SrcSections'], ',')) {
                $this->moduleParams['SrcSections'] = $this->tools->getArrayOfIntegersFromString($item['SrcSections']);
            } else {
                $this->moduleParams['SrcSections'] = (int) $item['SrcSections'];
            }
        }
        
        if (0 == $itemId && 0 != $this->moduleParams['TypeId']) {
            $typeId = $this->moduleParams['TypeId'];
        } else {
            $typeId = (int) $item['TypeId'];
        }
        
        $class = $this->getModuleName($typeId);
        if (null !== $class) { //module widgets
            $class = ucfirst($class) . '\\Widget' . ucfirst($this->getWidgetName($typeId));
        } else { //standalone widgets
            $class = 'Widgets\\' . ucfirst($this->getWidgetName($typeId));
        }
        
        $class = '\\Ufocms\\AdminModules\\' . $class;
        if (!class_exists($class)) {
            $class = '\\Ufocms\\AdminModules\\Widget';
            if (!class_exists($class)) {
                return;
            }
        }
        
        $container = $this->core->getContainer([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this->core, 
            'module'        => &$this->module, 
            'moduleParams'  => &$this->moduleParams, 
            'tools'         => &$this->tools, 
            'WidgetId'      => $itemId, 
            'TypeId'        => $typeId, 
        ]);
        $this->widget = new $class($container);
    }
    
    /**
     * @return Widget
     */
    public function getWidget()
    {
        $this->setWidget();
        return $this->widget;
    }
    
    /**
     * @return bool
     */
    public function getWidgetUseContent()
    {
        $this->setWidget();
        if (null === $this->widget) {
            return false;
        }
        return $this->widget->getUseContent();
    }
    
    /**
     * @return bool
     */
    public function getWidgetSingleSource()
    {
        $this->setWidget();
        if (null === $this->widget) {
            return false;
        }
        return $this->widget->getSingleSource();
    }
    
    /**
     * @return bool
     */
    public function getWidgetSourceDepends()
    {
        $this->setWidget();
        if (null === $this->widget) {
            return false;
        }
        return $this->widget->getSourceDepends();
    }
    
    public function getItem()
    {
        static $item = null;
        if (null === $item) {
            $item = parent::getItem();
            if (0 != $this->params->itemId) {
                $item['TrgSections'] = $this->getTargets();
            } else {
                $item['TrgSections'] = $this->getField('TrgSections')['Value'];
            }
        }
        return $item;
    }
    
    /**
     * Получение списка разделов размещения.
     * @param int $widgetId
     * @return array
     * @todo consider ItemId
     */
    protected function getTargets()
    {
        $sql =  'SELECT SectionId' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'widgets_targets' . 
                ' WHERE WidgetId=' . $this->params->itemId;
        return $this->db->getValues($sql, 'SectionId');
    }
    
    /**
     * @see parent
     */
    protected function fieldSkipped(array $field, $update = false)
    {
        $typeId = $update ? $this->getTypeId() : (int) $_POST['TypeId'];
        $moduleId = $this->getTypeModuleId($typeId);
        return 
            parent::fieldSkipped($field, $update) 
            || ('SrcSections' == $field['Name'] && 0 == $moduleId && !isset($_POST[$field['Name']])) //ignore not set SrcSections for moduleId=0
            || ('SrcItems' == $field['Name'] && !isset($_POST[$field['Name']])) //ignore not set SrcItems
            || ('Content' == $field['Name'] && !$this->getWidgetUseContent());
    }
    
    public function update()
    {
        if (null === $this->params->itemId) {
            $this->result = 'Request error: param `itemid` not set';
            return;
        }
        
        $data = $this->collectFormData($this->fields, 0 != $this->params->itemId);
        if (null === $data) {
            return;
        }
        $targets = $data['TrgSections']['Value'];
        unset($data['TrgSections']);
        
        $sql = $this->prepareSql($data, 0 == $this->params->itemId);
        if ($this->db->query($sql)) {
            $this->result = 'updated';
            if (0 == $this->params->itemId) {
                $this->lastInsertedId = $this->db->getLastInsertedId();
                $this->updateTargets($this->lastInsertedId, $targets);
            } else {
                $this->updateTargets($this->params->itemId, $targets);
            }
        } else {
            $this->result = 'DB error: ' . $this->db->getError();
        }
    }
    
    /**
     * Обновление привязок к разделам размещения.
     * @param int $widgetId
     * @param array $targets
     * @todo consider SrcItems
     */
    protected function updateTargets($widgetId, array $targets)
    {
        $targetsExists = $this->getTargets($widgetId);
        
        $targetsRemoved = array();
        foreach ($targetsExists as $te) {
            if (!in_array($te, $targets)) {
                $targetsRemoved[] = $te;
            }
        }
        if (0 < count($targetsRemoved)) {
            $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'widgets_targets' . 
                    ' WHERE WidgetId=' . $widgetId . 
                    ' AND SectionId IN(' . implode(',', $targetsRemoved) . ')';
            $this->db->query($sql);
            $targetsExists = array_diff($targetsExists, $targetsRemoved);
        }
        
        $targetsNew = array();
        foreach ($targets as $t) {
            if (!in_array($t, $targetsExists)) {
                $targetsNew[] = $t;
            }
        }
        if (0 < count($targetsNew)) {
            $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'widgets_targets' . 
                    ' (WidgetId,SectionId) VALUES ';
            $s = '';
            foreach ($targetsNew as $tn) {
                $s .= ',(' . $widgetId . ',' . $tn . ')';
            }
            $sql .= substr($s, 1);
            $this->db->query($sql);
        }
    }
    
    /**
     * @see parent
     */
    protected function actionAfterDelete()
    {
        $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'widgets_targets' . 
                ' WHERE WidgetId=' . $this->params->itemId;
        return $this->db->query($sql);
    }
    
    /**
     * Получение идентификатора типа виджета.
     * @return int
     */
    public function getTypeId()
    {
        static $typeId = null;
        if (null === $typeId) {
            $sql =  'SELECT TypeId' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'widgets' . 
                    ' WHERE Id=' . $this->params->itemId;
            $typeId = $this->db->getValue($sql, 'TypeId');
        }
        return $typeId;
    }
    
    /**
     * Получение идентификатора модуля по типу.
     * @param int $typeId
     * @return int
     */
    public function getTypeModuleId($typeId)
    {
        static $moduleId = null;
        if (null === $moduleId) {
            $sql =  'SELECT ModuleId' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'widgets_types' . 
                    ' WHERE Id=' . $typeId;
            $moduleId = $this->db->getValue($sql, 'ModuleId');
        }
        return $moduleId;
    }
}
