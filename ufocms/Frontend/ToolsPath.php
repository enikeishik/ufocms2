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
 * Вспомогательная функциональность для работы с путями.
 */
trait ToolsPath
{
    /**
     * Проверка строки содержащий путь раздела на соответствие шаблону 
     * `/some/section-path/` и отсутствие нежелательных символов (`//`, `..`).
     *
     * Для Windows систем надо добавить проверку отсутствия подстрок вида:
     * /(AUX|PRN|NUL|COM\d|CON|LPT\d)+\s/i
     *
     * @param string $str    проверяемое значение
     * @param bool $closingSlashRequired = false    обязательно наличие закрывающего слэша
     * @return bool
     */
    public function isPath($str, $closingSlashRequired = true)
    {
        if ($closingSlashRequired) {
            return (1 == preg_match('/^\/[a-z0-9~_\/\-\.]+\/$/i', $str)
                    && 0 == preg_match('/(\/{2})|(\.{2})/i', $str));
        } else {
            return (1 == preg_match('/^\/[a-z0-9~_\/\-\.]+$/i', $str)
                    && 0 == preg_match('/(\/{2})|(\.{2})/i', $str));
        }
    }
}
