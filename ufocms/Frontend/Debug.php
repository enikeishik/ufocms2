<?php
/**
 * @copyright
 */

namespace Ufocms\Frontend;

/**
 * Dubug application class
 */
class Debug
{
    /**
     * @var int
     */
    protected $debugStartTime = null;
    
    /**
     * @var array
     */
    protected $buffTrace = array();
    
    /**
     * @var array
     */
    protected static $buffErr = array();
    
    
    public function __construct()
    {
        $this->debugStartTime = microtime(true);
    }
    
    /**
     * @return float
     */
    public function getExecutionTime()
    {
        return microtime(true) - $this->debugStartTime;
    }
    
    /**
     * @param string|int $operation
     * @param mixed $errCode
     * @param string $errMessage
     * @return int
     */
    public function trace($operation = null, $errCode = null, $errMessage = null)
    {
        if (is_string($operation)) {
            $this->buffTrace[] = [
                'operation' => $operation, 
                'time'      => microtime(true), 
                'result'    => '', 
                'stack'     => 9 == C_DEBUG_LEVEL ? debug_backtrace() : null, 
            ];
            return count($this->buffTrace) - 1;
        } else {
            if (is_int($operation)) {
                $idx = $operation;
            } else {
                $idx = count($this->buffTrace) - 1;
            }
            $this->buffTrace[$idx]['time'] = round(microtime(true) - $this->buffTrace[$idx]['time'], 4);
            $this->buffTrace[$idx]['result'] = (null === $errCode ? 'OK' : '(' . $errCode . ') ' . $errMessage);
            return -1;
        }
    }
    
    /**
     * Set time to now-time for each unclosed trace items.
     */
    public function traceEnd()
    {
        $now = microtime(true);
        foreach ($this->buffTrace as &$traceItem) {
            if ('' === $traceItem['result']) {
                $traceItem['time'] = round($now - $traceItem['time'], 4);
            }
        }
        unset($traceItem);
    }
    
    /**
     * return int
     */
    public function getTraceCounter()
    {
        return count($this->buffTrace);
    }
    
    /**
     * @return array
     */
    public function getTrace()
    {
        return $this->buffTrace;
    }
    
    public static function errorHandler($errno, $errmsg, $file, $line)
    {
        self::$buffErr[] = $errno . "\t" . $file . "\t" . $line . "\t" . $errmsg;
    }
    
    /**
     * @return array
     */
    public static function getErrors()
    {
        return self::$buffErr;
    }
    
    /**
     * Вывод информации о переменной.
     * @param mixed $var
     * @param bool $dump = true
     * @param bool $exit = true
     * @param bool $float = false
     */
    public static function varDump($var, $dump = true, $exit = true, $float = false)
    {
        echo '<pre' . ($float ? ' class="debugfloat"' : '') . '>';
        ob_start();
        $dump ? var_dump($var) : print_r($var);
        echo htmlspecialchars(str_replace("=>\n", '  =>', ob_get_clean()));
        echo '</pre>';
        if ($exit) {
            exit();
        }
    }
}
