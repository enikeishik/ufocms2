<?php
/**
 * @copyright
 */

namespace Ufocms\AdminModules\Shop;

/**
 * News module model class
 */
class ModelImport extends \Ufocms\AdminModules\Model
{
    use Statistic, Aliases;
    
    const ERR_CATEGORY = 'Category is unset or not exists';
    const ERR_RELATEDINFO = 'Related info is unset or not exists';
    const ERR_FORM = 'Form error';
    const ERR_UPLOAD = 'Upload error';
    const ERR_OPERATION = 'Wrong operation';
    const ERR_CSV = 'CSV file error';
    const IMPORT_FIELDS = array('Id', 'Title', 'Thumbnail', 'Image', 'ShortDesc', 'FullDesc', 'Price');
    const SQL_INSERT_LIMIT = 100;
    
    /**
     * @var int
     */
    protected $categoryId = null;
    
    /**
     * @var int
     */
    protected $relatedInfoId = null;
    
    /**
     * @var string
     */
    protected $fileName = null;
    
    /**
     * @var string
     */
    protected $operation = null;
    
    /**
     * @var string
     */
    protected $delimiter = null;
    
    /**
     * @var string
     */
    protected $enclosure = null;
    
    /**
     * @var bool
     */
    protected $replace = null;
    
    /**
     * @var array
     */
    protected $data = array();
    
    
    /**
     * @see parent
     */
    protected function init()
    {
        parent::init();
        $this->itemsTable = 'shop_items';
        $this->itemDisabledField = '';
        $this->canDeleteItems = false;
        $this->categoryId = isset($_POST['categoryid']) ? (int) $_POST['categoryid'] : (isset($_GET['categoryid']) ? (int) $_GET['categoryid'] : 0);
        if (0 == $this->categoryId) {
            throw new \Exception(self::ERR_CATEGORY);
        }
        $this->relatedInfoId = 0;
        $this->primaryFilter .= ' AND CategoryId=' . $this->categoryId;
        $this->config->registerAction('import');
        $this->config->registerMakeAction('import');
        $this->params->actionUnsafe = false;
    }
    
    /**
     * @see parent
     */
    protected function setFields()
    {
        $this->fields = array(
            array('Type' => 'int',          'Name' => 'Id',             'Value' => 0,   'Title' => 'id',                'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => false),
            array('Type' => 'text',         'Name' => 'Title',          'Value' => '',  'Title' => 'Заголовок',         'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true,     'Required' => true),
            array('Type' => 'image',        'Name' => 'Thumbnail',      'Value' => '',  'Title' => 'Картинка мал.',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'image',        'Name' => 'Image',          'Value' => '',  'Title' => 'Картинка бол.',     'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'mediumtext',   'Name' => 'ShortDesc',      'Value' => '',  'Title' => 'Кратк. текст',      'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'bigtext',      'Name' => 'FullDesc',       'Value' => '',  'Title' => 'Полн. текст',       'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
            array('Type' => 'float',        'Name' => 'Price',          'Value' => 0,   'Title' => 'Цена',              'Filter' => false,  'Show' => true,     'Sort' => false,    'Edit' => true),
        );
    }
    
    /**
     * @return int
     */
    public function getCurrentCategoryId()
    {
        return $this->categoryId;
    }
    
    /**
     * @return array
     */
    public function getCategories()
    {
        static $categories = null;
        if (null === $categories) {
            $container = $this->core->getContainer([
                'debug'     => &$this->debug, 
                'config'    => &$this->config, 
                'params'    => &$this->params, 
                'db'        => &$this->db, 
                'core'      => &$this->core, 
            ]);
            $categories = new ModelCategories($container);
        }
        return $categories->getCategories();
    }
    
    /**
     * Checks for upload data file and form data.
     * @throws \Exception
     */
    protected function upload()
    {
        if (!isset($_FILES['file']) 
            || !isset($_FILES['file']['tmp_name'])
            || !isset($_POST['delimiter'])
            || !isset($_POST['enclosure'])
            || !isset($_POST['operation'])) {
            throw new \Exception(self::ERR_FORM);
        }
        if (!$this->categoryExists()) {
            throw new \Exception(self::ERR_CATEGORY);
        }
        $this->relatedInfoId = 0;
        /*
        $this->relatedInfoId = (int) $_POST[$this->fieldNameRelatedInfo];
        if (0 == $this->relatedInfoId || !$this->relatedInfoExists()) {
            throw new Exception(self::ERR_RELATEDINFO);
        }
        */
        $this->fileName = $_FILES['file']['tmp_name'];
        $this->operation = $_POST['operation'];
        $this->delimiter = $_POST['delimiter'];
        $this->enclosure = $_POST['enclosure'];
        $this->replace = isset($_POST['replace']);
    }
    
    /**
     * Parse uploaded data.
     * @throws \Exception
     */
    protected function parse()
    {
        if (!file_exists($this->fileName)) {
            throw new \Exception(self::ERR_UPLOAD);
        }
        if (false === $file = fopen($this->fileName, 'r')) {
            throw new \Exception(self::ERR_UPLOAD);
        }
        if ('insert' != $this->operation && 'update' != $this->operation) {
            throw new \Exception(self::ERR_OPERATION);
        }
        switch ($this->delimiter) {
            case ',':
                $delimiter = ',';
                break;
            case ';':
                $delimiter = ';';
                break;
            case ':':
                $delimiter = ':';
                break;
            case '|':
                $delimiter = '|';
                break;
            case 'tab':
                $delimiter = "\t";
                break;
            default:
                $delimiter = ',';
        }
        switch ($this->enclosure) {
            case "'":
                $enclosure = "'";
                break;
            default:
                $enclosure = '"';
        }
        while (false !== $data = fgetcsv($file, 0, $delimiter, $enclosure)) {
            $this->data[] = $data;
        }
        fclose($file);
    }
    
    /**
     * @return bool
     */
    protected function categoryExists() {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_categories' . 
                ' WHERE Id=' . $this->categoryId;
        return 0 != $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * @return bool
     */
    protected function relatedInfoExists() {
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_relatedinfo' . 
                ' WHERE Id=' . $this->relatedInfoId;
        return 0 != $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * @return int
     */
    protected function getMaxOrderNum() {
        $sql =  'SELECT MAX(OrderNumber) AS MaxOrderNum' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND CategoryId=' . $this->categoryId;
        return $this->db->getValue($sql, 'MaxOrderNum');
    }
    
    /**
     * Delete category items.
     * @return bool
     */
    protected function deleteOld() {
        $sql =  'DELETE FROM ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                ' WHERE SectionId=' . $this->params->sectionId . 
                ' AND CategoryId=' . $this->categoryId;
        return $this->db->query($sql);
    }
    
    /**
     * Insert items from data file into selected category.
     * @return int
     * @throws \Exception
     */
    protected function importNew() {
        $itemsCnt = 0;
        $sql = '';
        $cnt = 0;
        $orderNum = $this->getMaxOrderNum();
        $fieldsCnt = count(self::IMPORT_FIELDS) - 1;
        $aliases = array();
        foreach ($this->data as $data) {
            //Title, Thumbnail, Image, ShortDesc, FullDesc, Price
            if ($fieldsCnt != count($data)) {
                throw new \Exception(self::ERR_CSV);
            }
            $s = '';
            foreach ($data as $val) {
                $s .= "'" . $this->db->addEscape($val) . "',";
            }
            $sql .= '(' . 
                    $this->params->sectionId . ',' . 
                    $this->categoryId . ',' . 
                    $this->relatedInfoId . ',' . 
                    ++$orderNum . ',' . 
                    'NOW(),' . 
                    "'" . $this->getUnicAlias($this->getAliasFromText($data[0]), $aliases) . "'," . 
                    substr($s, 0, -1) . '),';
            if (++$cnt == self::SQL_INSERT_LIMIT) {
                $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                        ' (SectionId, CategoryId, RelatedInfoId, OrderNumber,' . 
                        ' DateCreate, Alias, Title, Thumbnail,' . 
                        ' Image, ShortDesc, FullDesc, Price)' . 
                        ' VALUES' . substr($sql, 0, -1) . ";\r\n";
                if ($this->db->query($sql)) {
                    $itemsCnt += $cnt;
                }
                $cnt = 0;
                $sql = '';
            }
        }
        unset($aliases);
        $sql =  'INSERT INTO ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                ' (SectionId, CategoryId, RelatedInfoId, OrderNumber,' . 
                ' DateCreate, Alias, Title, Thumbnail,' . 
                ' Image, ShortDesc, FullDesc, Price)' . 
                ' VALUES' . substr($sql, 0, -1) . ";\r\n";
        if ($this->db->query($sql)) {
            $itemsCnt += $cnt;
        }
        return $itemsCnt;
    }
    
    /**
     * @param int $itemId
     * @return bool
     */
    protected function itemExists($itemId)
    {
        if (0 >= $itemId) {
            return false;
        }
        $sql =  'SELECT COUNT(*) AS Cnt' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                ' WHERE Id=' . $itemId . 
                ' AND CategoryId=' . $this->categoryId;
        return 0 != $this->db->getValue($sql, 'Cnt');
    }
    
    /**
     * Update items by Id (if exists in DB) with data from data file.
     * @return int
     * @throws \Exception
     */
    protected function importExistings()
    {
        $itemsCnt = 0;
        $fieldsCnt = count(self::IMPORT_FIELDS);
        foreach ($this->data as $data) {
            //Id, Title, Thumbnail, Image, ShortDesc, FullDesc, Price
            if ($fieldsCnt != count($data)) {
                throw new \Exception(self::ERR_CSV);
            }
            $itemId = (int) $data[0];
            if (!$this->itemExists($itemId)) {
                continue;
            }
            $sql = '';
            for ($i = 1; $i < $fieldsCnt; $i++) {
                $sql .= " `" . self::IMPORT_FIELDS[$i] . "`='" . $this->db->addEscape($data[$i]) . "',";
            }
            $sql =  'UPDATE ' . C_DB_TABLE_PREFIX . 'shop_items' . 
                    ' SET' . 
                    substr($sql, 0, -1) . 
                    ' WHERE Id=' . $itemId;
            if ($this->db->query($sql)) {
                $itemsCnt++;
            }
        }
        return $itemsCnt;
    }
    
    /**
     * Import.
     * return bool
     */
    public function import()
    {
        try {
            $this->upload();
            $this->parse();
            //echo '<pre>'; var_dump($this->data); echo '</pre>';
            if ('insert' == $this->operation) {
                if ($this->replace) {
                    $this->deleteOld();
                }
                $cnt = $this->importNew();
            } else {
                $cnt = $this->importExistings();
            }
            $this->result = 'imported ' . $cnt . ' items';
            return true;
        } catch (\Exception $e) {
            $this->result = 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
