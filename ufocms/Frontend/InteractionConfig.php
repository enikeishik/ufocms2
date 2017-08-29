<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Класс конфигурации интерактивных составляющих.
 */
class InteractionConfig
{
    /**
     * Имя кука для предотвращения повторного голосования.
     * @var string
     */
    public $rateCookieName = 'interaction_voted';
    
    /**
     * Время жизни кука для предотвращения повторного голосования.
     * @var int
     */
    public $rateCookieLifetime = 31536000;
    
    /**
     * Добавление комментариев разрешено только зарегистрированным пользователям сайта.
     * @var bool
     */
    public $commentRequireRegistered = false;
    
    /**
     * Оценки комментариев разрешены только зарегистрированным пользователям сайта.
     * @var bool
     */
    public $commentRateRequireRegistered = false;
    
    /**
     * Голосования за материалы страниц разрешены только зарегистрированным пользователям сайта.
     * @var bool
     */
    public $rateRequireRegistered = false;
    
    /**
     * При добавлении комментариев/оценок/голосов проверять referer.
     * @var bool
     */
    public $checkReferer = true;
    
    /**
     * Использовать CAPTCHA при добавлении комментариев.
     * @var bool
     */
    public $checkCaptcha = true;
    
    /**
     * Премодерирование комментариев.
     * @var bool
     */
    public $commentPremoderation = false;
    
    /**
     * Задержка между добавлениями комментариев, сек.
     * @var int
     */
    public $commentAddDelay = 0;
    
    /**
     * Задержка между оценками комментариев, сек.
     * @var int
     */
    public $commentRateDelay = 0;
    
    /**
     * Задержка между оценками материала старницы, сек.
     * @var int
     */
    public $rateDelay = 0;
    
    /**
     * Использовать задержки для зарегистрированных пользователей.
     * @var bool
     */
    public $delayRegistered = false;
    
    /**
     * Максимальный объем комментария, байт.
     * @var int
     */
    public $commentMaxLength = 64000;
    
    /**
     * Минимальное значение статуса комментария.
     * @var int
     */
    public $commentStatusMin = -1;
    
    /**
     * Максимальное значение статуса комментария.
     * @var int
     */
    public $commentStatusMax = 1;
    
    /**
     * Значение статуса комментария по-умолчанию.
     * @var int
     */
    public $commentStatusDefault = 0;
    
    /**
     * Минимальное значение оценки комментария.
     * @var int
     */
    public $commentRateMin = -1;
    
    /**
     * Максимальное значение оценки комментария.
     * @var int
     */
    public $commentRateMax = 1;
    
    /**
     * Значение оценки комментария по-умолчанию.
     * @var int
     */
    public $commentRateDefault = 0;
    
    /**
     * Минимальное значение оценки материала страницы.
     * @var int
     */
    public $rateMin = 1;
    
    /**
     * Максимальное значение оценки материала страницы.
     * @var int
     */
    public $rateMax = 5;
    
    /**
     * Значение оценки материала страницы по-умолчанию.
     * @var int
     */
    public $rateDefault = 3;
    
    /**
     * Формат уведомлений в HTML.
     * @var bool
     */
    public $mailFormatHtml = false;
}
