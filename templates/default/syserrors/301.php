<?php
/**
 * @var \Ufocms\Frontend\Config $config
 * @var \Ufocms\Frontend\Params $params
 * @var array|null $site
 * @var string $location
 */

//header('HTTP/1.0 301 Moved Permanently');
header('Location: ' . $location);
?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>301 Moved Permanently</TITLE>
</HEAD><BODY>
<H1>Moved Permanently</H1>
The document has moved <a href="<?=htmlspecialchars($location)?>">here</a>.<P>
</BODY></HTML>
