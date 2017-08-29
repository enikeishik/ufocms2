<div class="debug">
<div>
    <?php echo 'time: ' . $this->debug->getExecutionTime() . '; mem: ' . memory_get_peak_usage(); ?>
    <?php if (C_DEBUG_LEVEL) { ?><span onclick="showHide('debug-this')">this</span><?php } ?>
    <span onclick="showHide('debug-trace')">trace</span>
</div>
<?php if (C_DEBUG_LEVEL) { ?>
<pre id="debug-this"><?php echo htmlspecialchars(var_dump($this)); //htmlspecialchars(print_r($this, true)); ?></pre>
<?php } ?>
<pre id="debug-trace">
<?php
$this->debug->traceEnd();
echo 'Total ' . $this->debug->getTraceCounter() . " operations traced:\r\n";
$trace = $this->debug->getTrace();
foreach ($trace as $traceItem) {
    echo    $traceItem['time'] . "\t" . 
            htmlspecialchars($traceItem['operation']) . 
            (null !== $traceItem['stack'] ? "\t" . 
            implode(
                '\\', 
                (function ($stack) {
                    $arr = [];
                    foreach ($stack as $s) {
                        if (isset($s['class'])) {
                            $a = explode('\\', $s['class']);
                            $arr[] = array_pop($a) . '::' . $s['function'];
                        } else {
                            $arr[] = $s['function'];
                        }
                    }
                    array_shift($arr);
                    array_shift($arr);
                    array_shift($arr);
                    return array_reverse($arr);
                })($traceItem['stack'])
            ) : '') . 
            "\r\n";
}
?>
</pre>
<script stype="text/javascript">showHide('debug-this');showHide('debug-trace');</script>
<?php
$errors = $this->debug->getErrors();
if (0 < $errcnt = count($errors)) {
    echo "<pre style='background-color: #fcc;'>\r\n";
    echo 'Total ' . $errcnt . " errors:\r\n";
    foreach ($errors as $err) {
        echo htmlspecialchars($err) . "\r\n";
    }
    echo "</pre>\r\n";
}
?>
</div>
