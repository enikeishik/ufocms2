<div class="debug">
    <div>
        <?php echo 'time: ' . $this->debug->getExecutionTime() . '; mem: ' . memory_get_peak_usage(); ?>
        <span onclick="document.getElementById('debug-trace').style.display = 'none' == document.getElementById('debug-trace').style.display ? '' : 'none'">trace</span>
    </div>
    <pre id="debug-trace">
    <?php
    echo 'Total ' . $this->debug->getTraceCounter() . " operations traced:\r\n";
    $trace = $this->debug->getTrace();
    foreach ($trace as $traceItem) {
        echo    $traceItem['time'] . "\t" . 
                htmlspecialchars($traceItem['operation']) . 
                (null !== $traceItem['stack'] ? "\t" . implode('\\', (function ($stack) { $arr = []; foreach ($stack as $s) { $arr[] = array_pop(explode('\\', $s['class'])) . '::' . $s['function']; } array_shift($arr); array_shift($arr); array_shift($arr); return array_reverse($arr); })($sql['stack'])) : '') . 
                "\r\n";
    }
    ?>
    </pre>
    <script type="text/javascript">document.getElementById('debug-trace').style.display = 'none';</script>
</div>
