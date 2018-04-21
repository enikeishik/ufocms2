<?php
/**
 * @var \Ufocms\Frontend\Config $config
 * @var \Ufocms\Frontend\Params $params
 * @var array|null $site
 */

header('HTTP/1.0 403 Forbidden');
?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>403 Forbidden</TITLE>
</HEAD><BODY>
<H1>Forbidden</H1>
You don't have permission to access page 
<?=htmlspecialchars($params->pathRaw)?> 
on this server.<P>
More information about this error may be available
in the error log `/logs/wr<?=date('ymd')?>.log`.<P>
<CODE>Error: <?=$errMsg?></CODE><P>
</BODY></HTML>
