<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Frontend;

/**
 * Core functionality and data
 */
class Core
{
    /**
     * @var Debug
     */
    protected $debug = null;
    
    /**
     * @var Config
     */
    protected $config = null;
    
    /**
     * @var Params
     */
    protected $params = null;
    
    /**
     * @var Db
     */
    protected $db = null;
    
    /**
     * @var array
     */
    protected $site = null;
    
    /**
     * @var array
     */
    protected $section = null;
    
    /**
     * @var array
     */
    protected $module = null;
    
    /**
     * @var Users
     */
    protected $users = null;
    
    /**
     * @var Quotes
     */
    protected $quotes = null;
    
    /**
     * @var Comments
     */
    protected $comments = null;
    
    /**
     * @var Interaction
     */
    protected $interaction = null;
    
    /**
     * @var InteractionManage
     */
    protected $interactionManage = null;
    
    /**
     * @var array
     */
    protected $sectionById = array();
    
    /**
     * @var array
     */
    protected $widgetsData = null;
    
    /**
     * @var array
     */
    protected $insertionsData = null;
    
    /**
     * @param Config &$config
     * @param Params &$params
     * @param Db &$db
     * @param Debug &$debug = null
     */
    public function __construct(Config &$config, Params &$params, Db &$db, Debug &$debug = null)
    {
        $this->debug =& $debug;
        $this->config =& $config;
        $this->params =& $params;
        $this->db =& $db;
    }
    
    /**
     * Инициализация данных текущего раздела.
     */
    public function setCurrentSection()
    {
        if (!$this->params->systemPath) {
            $this->section = $this->getSection($this->params->sectionPath);
            $this->params->sectionId = $this->section['id'];
        } else {
            $class = '\\Ufocms\\Modules\\' . $this->params->moduleName . '\\Section';
            $object = new $class();
            $this->section = $object->section;
            $this->section['path'] = $this->params->sectionPath;
            $this->params->sectionId = 0;
        }
    }
    
    /**
     * Проверка существования заданного пути в БД.
     * @param string $path    проверяемый путь (данные не проверяются на корректность!)
     * @return boolean
     */
    public function isPathExists($path)
    {
        $sql = 'SELECT COUNT(*) AS Cnt' . 
               ' FROM ' . C_DB_TABLE_PREFIX . 'sections' .
               " WHERE path='" . $path . "'";
        return 0 != $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * Получение максимально полного пути из переданного набора путей.
     * @param array $paths    проверяемый набор путей (данные не проверяются на корректность!)
     * @return string|null
     */
    public function getMaxExistingPath(array $paths)
    {
        $sql = 'SELECT path FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
               " WHERE path IN('" . implode("','", $paths) . "')" . 
               ' ORDER BY path DESC' . 
               ' LIMIT 1';
        return $this->db->getValue($sql, 'path');
    }
    
    /**
     * Get common site settings
     * @return array
     */
    public function getSite()
    {
        if (is_null($this->site)) {
            $sql =  'SELECT PName, PValue' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'siteparams';
            $params = $this->db->getItems($sql);
            foreach ($params as $param) {
                $this->site[$param['PName']] = $param['PValue'];
            }
        }
        return $this->site;
    }
    
    /**
     * Get current section settings
     * @return array
     */
    public function getCurrentSection()
    {
        return $this->section;
    }
    
    /**
     * Get SQL expression for fields
     * @param string|array|null $fields = null
     * @return string
     */
    protected function getFieldsSql($fields = null)
    {
        if (is_null($fields)) {
            return '*';
        } else if (is_array($fields)) {
            return implode(',', $fields);
        }
        return (string) $fields;
    }
    
    /**
     * Get (current) section information
     * @param int|string|null $section = null
     * @param string|array|null $fields = null
     * @return array|null
     */
    public function getSection($section = null, $fields = null)
    {
        if (is_string($section)) {
            $sql =  'SELECT ' . $this->getFieldsSql($fields) . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                    " WHERE path='" . $section . "'";
            return $this->db->getItem($sql);
        } else if (is_int($section)) {
            if (isset($this->sectionById[$section])) {
                return $this->sectionById[$section];
            }
            $sql =  'SELECT ' . $this->getFieldsSql($fields) . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                    ' WHERE id=' . $section;
            $this->sectionById[$section] = $this->db->getItem($sql);
            return $this->sectionById[$section];
        } else if (is_null($this->section)) {
            if (!is_null($this->params->sectionPath) 
            && '' != $this->params->sectionPath) {
                $sql =  'SELECT ' . $this->getFieldsSql($fields) . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                        " WHERE path='" . $this->params->sectionPath . "'";
                return $this->db->getItem($sql);
            } else if (!is_null($this->params->sectionId)
            && 0 != $this->params->sectionId) {
                $sql =  'SELECT ' . $this->getFieldsSql($fields) . 
                        ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                        ' WHERE id=' . $this->params->sectionId;
                return $this->db->getItem($sql);
            }
        }
        return null;
    }
    
    /**
     * Get section module information
     * @param string|array|null $fields = null
     * @return array|null
     */
    public function getModule($fields = null)
    {
        if (null === $this->module) {
            $section = (null !== $this->section ? $this->section : $this->getSection(null, 'moduleid'));
            if (null === $section) {
                return null;
            }
            $sql =  'SELECT ' . $this->getFieldsSql($fields) .
                    ' FROM ' . C_DB_TABLE_PREFIX . 'modules' .
                    ' WHERE muid=' . $section['moduleid'];
            $this->module = $this->db->getItem($sql);
        }
        return $this->module;
    }
    
    /**
     * Get sections for building treeview
     * @param string|array|null $fields = null
     * @param string $filter = null
     * @return array
     */
    public function getSections($fields = null, $filter = null)
    {
        $sql =  'SELECT ' . $this->getFieldsSql($fields) .
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE id!=-1 AND isenabled!=0' . 
                (null === $filter ? '' : ' AND ' . $filter) . 
                ' ORDER BY mask';
        return $this->db->getItems($sql);
    }
    
    /**
     * Get sections with given module ID
     * @param int $moduleId
     * @return array
     */
    public function getModuleSections($moduleId)
    {
        $sql =  'SELECT id, levelid, indic' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                ' WHERE moduleid=' . $moduleId . ' AND isenabled!=0' . 
                ' ORDER BY mask';
        $items = $this->db->getItems($sql);
        return null !== $items ? $items : array();
    }
    
    /**
     * Get section parents chain
     * @param int $sectionId
     * @return array
     */
    public function getSectionParentsRecursive($sectionId)
    {
        if (0 >= $sectionId) {
            return array();
        }
        $arr = array();
        $parentId = $sectionId;
        do {
            $sql =  'SELECT parentid' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                    ' WHERE id=' . $parentId;
            $parentId = $this->db->getValue($sql, 'parentid');
            if (!is_null($parentId) && 0 != $parentId) {
                $arr[] = $parentId;
            }
        } while (0 != $parentId);
        return array_reverse($arr);
    }
    
    /**
     * Get (current) section information
     * @param string|array|null $fields = null
     * @return array|null
     */
    public function getSectionParents($fields = null)
    {
        if (null === $this->section) {
            return null;
        } else if (1 > $this->section['levelid']) {
            return null;
        }
        $cpl = $this->config->maskCharsPerLevel;
        $masks = array();
        for ($i = 0, $end = $this->section['levelid'] + 1; $i < $end; $i++) {
            $masks[] = (0 < $i ? $masks[$i - 1] : '') . substr($this->section['mask'], $i * $cpl, $cpl);
        }
        $sql =  'SELECT ' . $this->getFieldsSql($fields) . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections' . 
                " WHERE mask IN('" . implode("','", $masks) . "')" . 
                ' ORDER BY mask';
        return $this->db->getItems($sql);
    }
    
    /**
     * Получение данных вставки. Один SQL запрос на все данные текущей страницы.
     * @param int $targetId      идентификатор раздела в котором выводится вставка
     * @param int $placeId       идентификатор места в котором выводится вставка
     * @param int $offset = 0    выбирать элементы начиная с $offset
     * @param int $limit = 0     выбрать всего $limit элементов (если $limit > 0)
     * @return array|null
     */
    public function getWidgetsData($targetId, $placeId, $offset = 0, $limit = 0)
    {
        if (null === $this->widgetsData) {
            $sql =  'SELECT w.PlaceId, w.OrderId, ' . 
                        'w.ShowTitle, w.SrcSections, w.SrcItems, w.Title, w.Content, w.Params, ' . 
                        'wt.ModuleId, wt.Name, m.madmin' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'widgets AS w' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'widgets_targets AS wtrg ON wtrg.WidgetId = w.Id' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'widgets_types AS wt ON wt.Id = w.TypeId' . 
                    ' LEFT JOIN ' . C_DB_TABLE_PREFIX . 'modules AS m ON m.muid = wt.ModuleId' . 
                    ' WHERE w.IsDisabled=0' . 
                    ' AND (wtrg.SectionId=' . $targetId . ' OR wtrg.SectionId=0)';
            $items = $this->db->getItems($sql);
            $items = $items ?? [];
            usort(
                $items, 
                function ($a, $b) {
                    if ($a['OrderId'] == $b['OrderId']) {
                        return 0;
                    }
                    return ($a['OrderId'] < $b['OrderId']) ? -1 : 1;
                }
            );
            $this->widgetsData = array();
            foreach ($items as $item) {
                $this->widgetsData[$item['PlaceId']][] = $item;
            }
            unset($items);
        }
        if (!isset($this->widgetsData[$placeId])) {
            return array();
        }
        $data = $this->widgetsData[$placeId];
        if (0 != $offset && 0 != $limit) {
            return array_slice($data, $offset, $limit);
        } else if (0 != $limit) {
            return array_slice($data, 0, $limit);
        }
        return $data;
    }
    
    /**
     * Получение данных вставки. Новая версия, делающая один SQL запрос на все данные.
     * @param int $targetId      идентификатор раздела в котором выводится вставка
     * @param int $placeId       идентификатор места в котором выводится вставка
     * @param int $offset = 0    выбирать элементы начиная с $offset
     * @param int $limit = 0     выбрать всего $limit элементов (если $limit > 0)
     * @return array|null
     * @deprecated
     */
    public function getInsertionsData($targetId, $placeId, $offset = 0, $limit = 0)
    {
        if (null === $this->insertionsData) {
            $sql =  'SELECT i.Id, i.TargetId, i.PlaceId, i.OrderId, i.SourceId, i.SourcesIds, ' . 
                    'i.Title, i.ItemsIds, i.ItemsStart, i.ItemsCount, i.ItemsLength, ' . 
                    'i.ItemsStartMark, i.ItemsStopMark, i.ItemsOptions, ' . 
                    's.path, m.madmin' . 
                    ' FROM ' . C_DB_TABLE_PREFIX . 'insertions AS i' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'sections AS s ON s.id=i.SourceId' . 
                    ' INNER JOIN ' . C_DB_TABLE_PREFIX . 'modules AS m ON m.muid=s.moduleid' . 
                    ' WHERE s.isenabled!=0 AND m.isenabled!=0';
            $items = $this->db->getItems($sql);
            $items = $items ?? [];
            $items2 = array();
            foreach ($items as $item) {
                $items2[$item['TargetId']][] = $item;
            }
            unset($items);
            $this->insertionsData = array();
            foreach ($items2 as $key => $val) {
                foreach ($val as $v) {
                    $this->insertionsData[$key][$v['PlaceId']][] = $v;
                }
            }
            unset($items2);
        }
        $tgtSet = 0 != $targetId ? isset($this->insertionsData[$targetId][$placeId]) : false;
        $allSet = isset($this->insertionsData[0][$placeId]);
        if ($tgtSet && $allSet) {
            $data = array_merge(
                $this->insertionsData[$targetId][$placeId], 
                $this->insertionsData[0][$placeId]
            );
        } else if ($tgtSet) {
            $data = $this->insertionsData[$targetId][$placeId];
        } else if ($allSet) {
            $data = $this->insertionsData[0][$placeId];
        } else {
            return array();
        }
        if (0 != $offset && 0 != $limit) {
            return array_slice($data, $offset, $limit);
        } else if (0 != $limit) {
            return array_slice($data, 0, $limit);
        }
        return $data;
    }
    
    /**
     * @return Users
     */
    public function getUsers()
    {
        if (null === $this->users) {
            $this->users = new Users($this->config, $this->params, $this->db, $this->debug);
        }
        return $this->users;
    }
    
    /**
     * @return Quotes
     */
    public function getQuotes()
    {
        if (null === $this->quotes) {
            $this->quotes = new Quotes($this->db);
        }
        return $this->quotes;
    }
    
    /**
     * @return Comments
     */
    public function getComments()
    {
        if (null === $this->comments) {
            $this->comments = new Comments($this->config, $this->params, $this->db, $this->debug);
        }
        return $this->comments;
    }
    
    /**
     * @return Interaction
     */
    public function getInteraction()
    {
        if (null === $this->interaction) {
            $this->interaction = new Interaction($this->config, $this->params, $this->db, $this->debug);
        }
        return $this->interaction;
    }
    
    /**
     * @return InteractionManage
     */
    public function getInteractionManage()
    {
        if (null === $this->interactionManage) {
            $this->interactionManage = new InteractionManage($this->config, $this->params, $this->db, $this->debug);
        }
        return $this->interactionManage;
    }
    
    /**
     * @param array $vars = null
     * @return Container
     */
    public function getContainer(array $vars = null)
    {
        return new Container($vars);
    }
    
    /**
     * @param int $errNum
     * @param string $errMsg = null
     * @param mixed $options = null
     */
    public function riseError($errNum, $errMsg = null, $options = null)
    {
        $container = new Container([
            'debug'         => &$this->debug, 
            'config'        => &$this->config, 
            'params'        => &$this->params, 
            'db'            => &$this->db, 
            'core'          => &$this, 
        ]);
        $error = new Error($container);
        $error->rise($errNum, $errMsg, $options);
    }
}
