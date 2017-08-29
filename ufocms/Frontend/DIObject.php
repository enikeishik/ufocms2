<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Dependency injection object
 */
abstract class DIObject
{
    /**
     * Ссылка на объект-контейнер ссылок на объекты.
     * @var Container
     */
    protected $container = null;
    
    /**
     * Конструктор.
     * @param Container &$container    ссылка на объект-контейнер ссылок на объекты
     */
    public function __construct(Container &$container)
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
