<?php
/**
 * UFOCMS v2 Content Management System
 * 
 * @copyright   Copyright (C) 2005 - 2017 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Ufocms\Modules;

use Ufocms\Frontend\DIObject;

/**
 * Module level XmlSitemap generate base class
 */
abstract class XSM extends DIObject implements XSMInterface
{
    /**
     * @var \Ufocms\Frontend\Debug
     */
    protected $debug = null;
    
    /**
     * Ссылка на объект конфигурации.
     * @var \Ufocms\Frontend\Config
     */
    protected $config = null;
    
    /**
     * @var \Ufocms\Frontend\Db
     */
    protected $db = null;
    
    /**
     * @var \Ufocms\Frontend\XmlSitemap
     */
    protected $xmlSitemap = null;
    
    /**
     * @var array<int id, string path>
     */
    protected $section = null;
    
    /**
     * Section path with closing slash
     * @var string
     */
    protected $sectionPath = null;
    
    /**
     * @var int
     */
    protected $itemsCount = null;
    
    /**
     * Распаковка контейнера.
     */
    protected function unpackContainer()
    {
        $this->debug        =& $this->container->getRef('debug');
        $this->config       =& $this->container->getRef('config');
        $this->db           =& $this->container->getRef('db');
        $this->xmlSitemap   =& $this->container->getRef('xmlSitemap');
        $this->section      =  $this->container->get('section');
    }
    
    /**
     * Инициализация объекта. Переобпределяется в потомках по необходимости.
     */
    protected function init()
    {
        if ('/' == $this->section['path'][strlen($this->section['path']) - 1]) {
            $this->sectionPath = $this->section['path'];
        } else {
            $this->sectionPath = $this->section['path'] . '/';
        }
        $this->itemsCount = 0;
    }
    
    /**
     * Генерация элементов.
     */
    public function generate()
    {
        $items = $this->getItems();
        $this->itemsCount = count($items);
        
        //страницы могут быть не связны с постраничным выводом списка элементов
        //поэтому генерация страниц осуществляется независимо от itemsCount
        $this->generatePages();
        
        if (0 < $this->itemsCount) {
            $this->xmlSitemap->output(
                $this->xmlSitemap->xmlItems(
                    $this->getItemsParams(), 
                    $items
                )
            );
            $this->xmlSitemap->incItemsCounter($this->itemsCount);
        }
    }
    
    /**
     * Генерация страниц (например, при постраничном выводе элементов).
     */
    protected function generatePages()
    {
        $pagesCount = $this->getPagesCount();
        $items = array();
        for ($i = 2; $i <= $pagesCount; $i++) {
            $items[] = array('path' => $this->getPagePath($i));
        }
        $this->xmlSitemap->output(
            $this->xmlSitemap->xmlItems(
                $this->getPagesParams(), 
                $items
            )
        );
        $this->xmlSitemap->incItemsCounter(count($items));
    }
    
    /**
     * @return array
     */
    protected function getItemsParams()
    {
        return array(
            'lastmod'       => date('Y-m-d'), 
            'changefreq'    => 'weekly', 
            'priority'      => '0.5', 
        );
    }
    
    /**
     * @return array
     */
    protected function getPagesParams()
    {
        return $this->getItemsParams();
    }
    
    /**
     * Вычисление количества страниц.
     * Здесь вычисляется количество страниц при постраничном выводе элементов.
     * Если требуется определять количество страниц по другим критериям, следует переопределить данный метод.
     * @return int
     */
    protected function getPagesCount()
    {
        if (0 == $pageLength = $this->getPageLength()) {
            return 0;
        } else {
            return ceil($this->itemsCount / $pageLength);
        }
    }
    
    /**
     * @param int $page
     * @return string
     */
    protected function getPagePath($page)
    {
        return $this->sectionPath . 'page' . $page;
    }
    
    /**
     * @return int
     */
    abstract protected function getPageLength();
    
    /**
     * @return array
     */
    abstract protected function getItems();
}
