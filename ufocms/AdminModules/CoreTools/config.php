<?php
return [
    'systemBackups' => [
        '/.htaccess', 
        '/autoload.php', 
        '/captcha.php', 
        '/index.php', 
        '/interaction.php', 
        '/presets.php', 
        '/quotes.php', 
        '/xsm.php', 
        // /admin, 
        '/ufocms', 
        '/templates/default', 
    ], 
    
    'userBackups' => [
        '/config.php', 
        '/favicon.ico', 
        '/robots.txt', 
        '/templates', 
    ], 
    
    'localUpdateCheck' => '/index.php', 
    
    'repositoryUrl' => 'https://github.com/enikeishik/ufocms2', 
    
    'repositoryApiUrl' => 'https://api.github.com/repos/enikeishik/ufocms2/commits', 
    
    'moduleStruct' => [
        'dir' => [
            '/admin/templates/', 
            '/templates/default/', 
            '/ufocms/AdminModules/', 
            '/ufocms/Modules/', 
        ], 
        'sql'   => '/*.sql', 
    ], 
    
    'installedDirMode' => 0777, 
];
