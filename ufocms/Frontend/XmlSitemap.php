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
 * Класс генерации XmlSitemap.
 */
class XmlSitemap
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
     * @var Db
     */
    protected $db = null;
    
    /**
     * @var string
     */
    protected $serverName = null;
    
    /**
     * @var string
     */
    protected $lastModified = null;
    
    /**
     * @var int
     */
    protected $itemsCounter = null;
    
    /**
     * @var float
     */
    protected $startTime = null;
    
    /**
     * @var resource
     */
    protected $fileHandle = null;
    
    /**
     * @var string
     */
    protected $buffer = '';
    
    /**
     * @var int
     */
    protected $bufferLength = 10240; //10k
    
    /**
     * @param Config &$config
     * @param Db &$db
     * @param Debug &$debug = null
     */
    public function __construct(&$config, &$db, &$debug = null)
    {
        if (!$this->check()) {
            exit();
        }
        
        $this->config   =& $config;
        $this->debug    =& $debug;
        $this->db       =& $db;
        
        $this->serverName = $_SERVER['SERVER_NAME'];
        $this->lastModified = date('Y-m-d');
        $this->itemsCounter = 0;
    }
    
    public function check()
    {
        return  isset($_SERVER['HTTP_REFERER']) 
                && false !== strpos($_SERVER['HTTP_REFERER'], str_ireplace('www.', '', $_SERVER['HTTP_HOST']));
    }
    
    public function generate()
    {
        $this->messageStart();
        
        $path = $this->config->rootPath . $this->config->xmlSitemapPath;
        if (file_exists($path) && !is_writable($path)) {
            exit('Xmlsitemap file NOT writable');
        }
        $this->fileHandle = fopen($path, 'w');
        if (!$this->fileHandle) {
            exit('Fail to open xmlsitemap file for writing');
        }
        
        $this->output($this->xmlHeader());
        $this->output($this->xmlSite());
        $this->incItemsCounter();
        $this->walkSections();
        $this->output($this->xmlFooter());
        $this->write(); //write buffer tail
        
        fclose($this->fileHandle);
        
        $this->messageStop();
    }
    
    /**
     * @param string $str
     */
    public function output($str)
    {
        $this->buffer .= $str;
        
        if ($this->bufferLength < strlen($this->buffer)) {
            $this->write();
            $this->buffer = '';
        }
    }
    
    /**
     * Генерация XML для одного элемента.
     * @param array $item
     * @return string
     */
    public function xmlItem($item)
    {
        return  '<url>' . 
                '<loc>http://' . 
                    $this->serverName . 
                    ('/' == $item['path'][strlen($item['path']) - 1] ? substr($item['path'], 0, -1) : $item['path']) . 
                '</loc>' . 
                '<lastmod>' . (array_key_exists('lastmod', $item) ? $item['lastmod'] : $this->lastModified) . '</lastmod>' . 
                '<changefreq>' . (array_key_exists('changefreq', $item) ? $item['changefreq'] : 'weekly') . '</changefreq>' . 
                '<priority>' . (array_key_exists('priority', $item) ? $item['priority'] : '0.5') . '</priority>' . 
                '</url>' . "\r\n";
    }
    
    /**
     * Генерация XML для списка элементов.
     * @param array $params
     * @param array $items
     * @return string
     */
    public function xmlItems($params, $items)
    {
        $lastmod    = array_key_exists('lastmod', $params)      ? $params['lastmod']        : $this->lastModified;
        $changefreq = array_key_exists('changefreq', $params)   ? $params['changefreq']     : 'weekly';
        $priority   = array_key_exists('priority', $params)     ? $params['priority']       : '0.5';
        $s = '';
        foreach ($items as $item) {
            $s .=   '<url>' . 
                    '<loc>http://' . 
                        $this->serverName . 
                        ('/' == $item['path'][strlen($item['path']) - 1] ? substr($item['path'], 0, -1) : $item['path']) . 
                    '</loc>' . 
                    '<lastmod>' . $lastmod . '</lastmod>' . 
                    '<changefreq>' . $changefreq . '</changefreq>' . 
                    '<priority>' . $priority . '</priority>' . 
                    '</url>' . "\r\n";
        }
        return $s;
    }
    
    /**
     * Увеличение счетчика обработанных элементов.
     * @param int $count = 1
     */
    public function incItemsCounter($count = 1)
    {
        $this->itemsCounter += $count;
    }
    
    /**
     * @return string
     */
    protected function xmlHeader()
    {
        return  '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . 
                '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\r\n";
    }
    
    /**
     * @return string
     */
    protected function xmlFooter()
    {
        return '</urlset>' . "\r\n";
    }
    
    /**
     * @return string
     */
    protected function xmlSite()
    {
        return  '<url>' . 
                '<loc>http://' . $this->serverName . '</loc>' . 
                '<lastmod>' . $this->lastModified . '</lastmod>' . 
                '<changefreq>daily</changefreq>' . 
                '<priority>0.9</priority>' . 
                '</url>' . "\r\n";
    }
    
    /**
     * @param array $section
     * @return string
     */
    protected function xmlSection(array $section)
    {
        return  '<url>' . 
                '<loc>http://' . 
                    $this->serverName . 
                    ('/' == $section['path'][strlen($section['path']) - 1] ? substr($section['path'], 0, -1) : $section['path']) . 
                '</loc>' . 
                '<lastmod>' . $this->lastModified . '</lastmod>' . 
                '<changefreq>daily</changefreq>' . 
                '<priority>0.8</priority>' . 
                '</url>' . "\r\n";
    }
    
    protected function walkSections()
    {
        $sql =  'SELECT s.id,s.path,m.madmin' . 
                ' FROM ' . C_DB_TABLE_PREFIX . 'sections AS s, ' . C_DB_TABLE_PREFIX . 'modules as m' . 
                ' WHERE s.moduleid=m.muid AND m.muid!=-1 AND s.isenabled!=0' . 
                    ' AND (insearch!=0 OR inmenu!=0 OR inlinks!=0 OR inmap!=0)' . 
                    ' AND m.isenabled!=0' . 
                ' ORDER BY s.mask';
        $sections = $this->db->getItems($sql);
        foreach ($sections as $section) {
            if (0 === strpos($section['path'], 'http')) {
                continue;
            }
            
            $this->output($this->xmlSection($section));
            $this->incItemsCounter();
            
            $class = '\\Ufocms\\Modules\\' . ucfirst(substr($section['madmin'], 4)) . '\\XSM';
            if (class_exists($class)) {
                $container = new Container([
                    'debug'         => &$this->debug, 
                    'config'        => &$this->config, 
                    'db'            => &$this->db, 
                    'xmlSitemap'    => &$this, 
                    'section'       => $section, 
                ]);
                $xsm = new $class($container);
                $xsm->generate();
            }
        }
    }
    
    protected function write()
    {
        if (!fwrite($this->fileHandle, $this->buffer)) {
            exit('Fail to write into xmlsitemap file');
        }
    }
    
    protected function messageStart()
    {
        $this->startTime = microtime(true);
        echo    '<html><head><title>Building xmlsitemap</title></head>' . 
                '<body><p>Building xmlsitemap...</p>';
        ob_flush();
        flush();
    }
    
    protected function messageStop()
    {
        echo    'Building complete: <a href="/sitemap.xml" target="_blank">sitemap.xml</a><br>' . 
                'Execution time: ' . (microtime(true) - $this->startTime) . '<br>' . 
                'Memory used: ' . memory_get_peak_usage() . '<br>' . 
                'Builded items: ' . $this->itemsCounter . '</body></html>';
    }
}
