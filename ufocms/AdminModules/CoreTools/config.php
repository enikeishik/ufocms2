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
];
