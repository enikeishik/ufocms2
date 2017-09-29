<div class="debug">
    <div><?php echo 'time: ' . $this->debug->getExecutionTime() . '; mem: ' . memory_get_peak_usage(); ?></div>
    <pre>
    <?php
    $this->debug->traceEnd();
    echo 'Total ' . $this->debug->getTraceCounter() . " operations traced:\r\n";
    $trace = $this->debug->getTrace();
    foreach ($trace as $traceItem) {
        echo    $traceItem['time'] . "\t" . 
                str_replace(' FROM ', ' <b>FROM</b> ', htmlspecialchars($traceItem['operation'])) . 
                (null !== $traceItem['stack'] ? "\t" . implode('\\', (function ($stack) { $arr = []; foreach ($stack as $s) { $arr[] = array_pop(explode('\\', $s['class'])) . '::' . $s['function']; } array_shift($arr); array_shift($arr); array_shift($arr); return array_reverse($arr); })($sql['stack'])) : '') . 
                "\r\n";
    }
    ?>
    </pre>
</div>
