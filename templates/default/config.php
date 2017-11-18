<?php
return [
    
    'captcha' => [
        'bgColor'           => [0xEE, 0xEE, 0xFF], 
        'shColor'           => [0xCC, 0xCC, 0xEE], 
        'fgColor'           => [0x99, 0x99, 0xCC], 
        'jpegQuality'       => 15, 
        'fontSize'          => 5, 
        'letterSeparator'   => ' ', 
    ], 
    
    'http' => [
        'headers' => [
            'Content-type: text/html; charset=utf-8', 
            'Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 600) . ' GMT', 
            'Cache-Control: max-age=' . 3600, 
            'Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT', 
        ], 
        'headersDebug' => [
            'Content-type: text/html; charset=utf-8', 
            'Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 999999) . ' GMT', 
            'Cache-Control: no-store, no-cache, must-revalidate', 
            'Expires: ' . gmdate('D, d M Y H:i:s', time() - 999999) . ' GMT', 
            'Pragma: no-cache', 
        ], 
    ], 
];
