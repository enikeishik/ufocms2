<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Класс вспомогательных функционалов.
 */
class Tools
{
    use ToolsPath;
    
    /**
     * @var Debug
     */
    protected $debug = null;
    
    /**
     * @var Config
     */
    protected $config = null;
    
    /**
     * @var array
     */
    protected $params = null;
    
    /**
     * @var Db
     */
    protected $db = null;
    
    /**
     * @var Messenger
     */
    protected $messenger = null;
    
    /**
     * Конструктор.
     * @param Config &$config
     * @param array &$params
     * @param Db &$db
     * @param Debug &$debug = null
     */
    public function __construct(&$config, &$params, &$db, &$debug = null)
    {
        $this->config   =& $config;
        $this->params   =& $params;
        $this->db       =& $db;
        $this->debug    =& $debug;
    }
    
    /**
     * Проверка строкового значения на соответствия числу.
     *
     * @param string $str    проверяемое значение
     * @param bool $unsigned = false    только неотрицательные
     *
     * @return boolean
     */
    public function isInt($str, $unsigned = false)
    {
        if (!$unsigned) {
            return (string) (int) $str === (string) $str;
        } else {
            return ctype_digit((string) $str) && ($str <= PHP_INT_MAX) && ($str > (PHP_INT_MAX * -1));
        }
    }
    
    /**
     * Проверяет содержит ли массив только значения типа int
     *
     * @param array $arr    массив проверяемых значений
     *
     * @return boolean
     */
    public function isArrayOfIntegers(array $arr)
    {
        foreach ($arr as $val) {
            if (!$this->isInt($val)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Проверяет содержит ли строка значения типа int разделенные разделителем.
     *
     * @param string $str    строка значений, разделенных разделителем
     * @param string $sep    строка-разделитель значений
     *
     * @return boolean
     */
    public function isStringOfIntegers($str, $sep = ',')
    {
        return $this->isArrayOfIntegers(
            array_map(
                function($str) { return trim($str); }, 
                explode($sep, $str)
            )
        );
    }
    
    /**
     * Получение массива целых чисел из строки содержащей числа с разделителями.
     * 
     * @param string $str    строка значений, разделенных разделителем
     * @param string $sep    строка-разделитель значений
     *
     * @return array<int>
     */
    public function getArrayOfIntegers($str, $sep = ',')
    {
        return array_map(
                function($str) { return (int) trim($str); }, 
                explode($sep, $str)
        );
    }
    
    /**
     * Проверка на соответствие Email адресу.
     *
     * @param string $str    проверяемое значение
     *
     * @return boolean
     */
    public function isEmail($str)
    {
        if (0 == strlen($str)) {
            return false;
        }
        return (bool) preg_match('/[a-z0-9_\-\.]+@[a-z0-9\-\.]{2,}\.[a-z]{2,6}/i', $str);
    }
    
    /**
     * Добавление экранирования к спецсимволам в строке для безопасного использования в JS.
     * 
     * @param string $str
     * @param bool $rawHtml = false
     * @return string
     */
    public function getSafeJsString($str, $rawHtml = false)
    {
        if (!$rawHtml) {
            return htmlspecialchars(addcslashes($str, "\0..\37\"\'\\"), ENT_NOQUOTES);
        } else {
            return addcslashes($str, "\0..\37\"\'\\");
        }
    }
    
    /**
     * Получение первого параграфа текста.
     * Если текст разбит на параграфы тэгами <p>, 
     * эта функция возвращает первый параграф 
     * (вместе с тэгами параграфа и замыкающим переводом строки).
     * Иначе возвращает пустую строку.
     *
     * @param string $str    исходный текст
     *
     * @return string
     */
    public function getFirstParagraph($str)
    {
        return substr($str, 0, stripos($str, '<p>', 3));
    }
    
    /**
     * Получение красиво обрезанного текста.
     * После обрезания по заданной длинне, 
     * производится дополнительное обрезание до последнего пробела.
     *
     * @param string  $str              исходный текст
     * @param int     $length           длинна отрезаемого текста
     * @param int     $offset = 0       смещение от начала текста
     * @param boolean $offset = true    очистить текст от тэгов перед обрезкой
     *
     * @return string
     */
    public function cutNice($str, 
                            $length, 
                            $offset = 0, 
                            $removeTags = true)
    {
        if (0 >= $length || 0 > $offset || '' == $str) {
            return '';
        }
        if ($removeTags) {
            $str = strip_tags($str);
        }
        $strLength = strlen($str);
        if ($strLength <= $offset) {
            return '';
        }
        return (($strLength > $length) 
                ? substr($str, 
                         $offset, 
                         strrpos(substr($str, $offset, $length), ' ')) 
                : trim($str));
    }
    
    /**
     * Получение текста заданной длинны из исходного, 
     * вырезая из текста середину и заменяя вырезанное на заглушку.
     *
     * @param string $str                исходный текст
     * @param int    $length             длинна получаемого текста
     * @param string $cutStub = '...'    чем будет заменена вырезанная часть
     *
     * @return string
     */
    public function cutMiddle($str, $length, $cutStub = '...')
    {
        $strLength = strlen($str);
        $cutStubLength = strlen($cutStub);
        if ($length >= $strLength) {
            return $str;
        } else if ($length < $cutStubLength) {
            return '';
        }
        $left = ceil($length / 2);
        $right = $strLength - $left;
        if (0 != ($cutStubLength % 2)) {
            $right++;
            if (0 != ($length % 2)) {
                $left--;
            }
        } else if (0 != ($length % 2)) {
            $right++;
        }
        $cutStubHalfLength = floor($cutStubLength / 2);
        return substr($str, 0, $left - $cutStubHalfLength)
               . $cutStub
               . substr($str, $right + $cutStubHalfLength);
    }
    
    /**
     * Обрезание текста по маркеру-разделителю в тексте.
     * Используется только для получения текста ДО разделителя,
     * поскольку разделитель может быть вложен в тэги, 
     * текст после разделителя может быть некорректным
     * при расположении разделителя не рядом с тэгами
     * (не на краю абзацев, а в середине текста),
     * функция ищет ближайший слева закрывающий тэг 
     * и обрезает сразу после него,
     * если установлен параметр $more = true,   
     * функция по возможности старается дополнить текст 
     * до ближайшего правого закрывающего тэга
     *
     * @param string  $str             исходный текст
     * @param string  $separator       разделитель, по которому происходит обрезание
     * @param boolean $more = false    определяет поведение обрезания в неоднозначных ситуациях, только когда разделитель не граничит с тэгами
     *
     * @return string|false
     */
    public function cutBySeparator($str, 
                                   $separator, 
                                   $more = false)
    {
        $pos = strpos($str, $separator);
        if (false === $pos) {
            return $str;
        }
        
        $separatorLength = strlen($separator);
        $left = substr($str, $pos - 1, 1);
        $left2 = substr($str, $pos - 2, 1);
        $right = substr($str, $pos + $separatorLength, 1);
        $right2 = substr($str, $pos + $separatorLength + 1, 1);
        
        /* DEBUG echo "\r\n<br />" . 'left2: ' . $left2 . '; left: ' . $left . '; right: ' . $right . '; right2: ' . $right2 . "<br />\r\n"; */
        
        //если слева стоит открывающий тэг, справа закрывающий тэг, обрезаем до левого тэга
        if ('>' == $left && '/' != $left2 && '<' == $right && '/' == $right2) {
            $str = substr($str, 0, $pos);
            $pos2 = strrpos($str, '<');
            if (false === $pos2) {
                return false;
            }
            /* DEBUG echo "\r\n<br />" . 'pos2: ' . $pos2 . "<br />\r\n"; */
            return substr($str, 0, $pos2);
            
        //если справа закрывающий тэг, включаем его
        } else if ('<' == $right && '/' == $right2) {
            $pos2 = strpos($str, '>', $pos + $separatorLength);
            if (false === $pos2) {
                return false;
            }
            /* DEBUG echo "\r\n<br />" . 'pos2: ' . $pos2 . "<br />\r\n"; */
            return str_replace($separator, '', substr($str, 0, $pos2 + 1));
            
        //если слева одиночный закрытый тэг (<img />, <br />, ...), включаем его
        } else if ('>' == $left && '/' == $left2) {
            return substr($str, 0, $pos);
            
        } else {
            $strLeft = substr($str, 0, $pos);
            /* DEBUG echo "\r\n<br />" . 'strLeft: ' . htmlspecialchars($strLeft) . "<br />\r\n"; */
            
            //если слева тэг, смотрим какой это тэг - открывающий или закрывающий
            if ('>' == $left) {
                $pos1 = strrpos($strLeft, '<');
                $pos2 = strrpos($strLeft, '</');
                
                if (false === $pos1) {
                    return false;
                }
                
                //если тэг слева закрывающий, обрезаем сразу после него
                if ($pos1 == $pos2) {
                    return $strLeft;
                //если слева открывающий тэг, обрезаем непосредственно перед ним
                } else {
                    /* DEBUG echo "\r\n<br />" . 'pos2: ' . $pos2 . "<br />\r\n"; */
                    return substr($str, 0, $pos1);
                }
                
            //если разделитель в тексте и не граничит с тэгами
            } else {
                //ищем ближайший закрывающий тэг слева и обрезаем до него
                if (!$more) {
                    $pos2 = strrpos($strLeft, '</');
                    if (false !== $pos2) {
                        $pos2 = strpos($strLeft, '>', $pos2 + 2);
                        if (false === $pos2) {
                            return false;
                        }
                        return substr($strLeft, 0, $pos2 + 1);
                    } else {
                        //закрывающих тэгов слева нет, проверяем есть ли вообще слева тэги
                        $pos2 = strrpos($strLeft, '<');
                        //если нет, возвращаем левую часть
                        if (false === $pos2) {
                            return $strLeft;
                        //иначе возвращаем пустую строку
                        } else {
                            return '';
                        }
                    }
                    
                //ищем ближайший закрывающий тэг справа, включаем его
                } else {
                    $strRight = substr($str, $pos + $separatorLength);
                    /* DEBUG echo "<hr width=\"50%\">\r\nstrRight: " . htmlspecialchars($strRight) . "<br />\r\n"; */
                    $pos2 = strpos($strRight, '</');
                    if (false !== $pos2) {
                        $pos2 = strpos($strRight, '>', $pos2 + 2);
                        if (false === $pos2) {
                            return false;
                        }
                        return $strLeft . substr($strRight, 0, $pos2 + 1);
                    } else {
                        //тэгов справа нет, просто возвращаем левую часть
                        return $strLeft;
                    }
                }
            }
        }
    }
    
    /**
     * Разбиение текста на части по маркеру-разделителю в тексте 
     * и выдача запрашиваемой части.
     * Использует функцию cutBySeparator
     *
     * @param string  $str             исходный текст
     * @param string  $separator       разделитель, по которому происходит обрезание
     * @param int     $part            номер запрашиваемой части (нумерация с нуля)
     * @param boolean $more = false    определяет поведение обрезания в неоднозначных ситуациях, только когда разделитель не граничит с тэгами
     *
     * @return string|false
     */
    public function getTextPartBySeparator($str, 
                                           $separator, 
                                           $part = 0, 
                                           $more = false)
    {
        if (0 > $part) {
            return false;
        }
        $arr = explode($separator, $str);
        if (false === $arr) {
            return false;
        }
        $arrCount = count($arr);
        if ($arrCount <= $part) {
            return false;
        }
        
        //если только один элемент, возвращаем его целиком
        if (1 == $arrCount) {
            return $str;
        }
        
        //последний элемент, ищем закрывающий тэг до открывающего 
        //и если находим, обрезаем сразу после него
        if (($arrCount - 1) == $part) {
            $str = $arr[$part];
            unset($arr);
            
            $pos = strpos($str, '<');
            $pos2 = strpos($str, '</');
            //если первый встречающийся тэг является закрывающим
            //обрезаем срезу после него
            if (false !== $pos2 && $pos2 == $pos) {
                $pos2 = strpos($str, '>', $pos2 + 2);
                if (false === $pos2) {
                    return false;
                }
                return substr($str, $pos2 + 1);
            } else {
                return $str;
            }
            
        //сцепляем нужный и следующий за ним элементы и применяем функцию api_GetCutTextBySeparator
        } else {
            $str = $arr[$part] . $separator . $arr[$part + 1];
            unset($arr);
            
            return $this->cutBySeparator($str, $separator, $more);
        }
    }
    
    /**
     * Получение значения атрибута src из тэга <img>.
     *
     * @param string $str    исходный текст
     *
     * @return string|false
     */
    public function srcFromImg($str)
    {
        $pos = stripos($str, 'src=');
        if (false === $pos) {
            return false;
        }
        
        $str = substr($str, $pos + 4);
        $pos = strpos($str, ' ');
        if (false !== $pos) {
            $str = substr($str, 0, $pos);
        } else {
            $pos = strpos($str, '>');
            if (false === $pos) {
                return false;
            }
            $str = substr($str, 0, $pos - 1);
            if ('/' == substr($str, strlen($str) - 2, 1)) {
                $str = substr($str, 0, strlen($str) - 2);
            }
        }
        
        $str = str_replace(array('"', "'"), '', $str);
        return $str;
    }
    
    /**
     * @return Captcha
     */
    public function getCaptcha()
    {
        //always return new object
        return new Captcha($this->config);
    }
    
    /**
     * @return Messenger
     */
    public function getMessenger()
    {
        if (null === $this->messenger) {
            $this->messenger = new Messenger($this->config);
        }
        return $this->messenger;
    }
}
