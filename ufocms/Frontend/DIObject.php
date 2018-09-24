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
 * Dependency injection object
 */
abstract class DIObject implements DIObjectInterface
{
    /**
     * Ссылка на объект-контейнер ссылок на объекты.
     * @var Container
     */
    protected $container = null;
    
    /**
     * Конструктор.
     * @param ContainerInterface &$container    ссылка на объект-контейнер ссылок на объекты
     */
    public function __construct(ContainerInterface &$container)
    {
        $this->container =& $container;
        $this->unpackContainer();
        unset($this->container);
        $this->init();
    }
    
    /**
     * Присванивание ссылок объектов контейнера локальным переменным.
     */
    abstract protected function unpackContainer();
    
    /**
     * Инициализация объекта.
     */
    abstract protected function init();
}
