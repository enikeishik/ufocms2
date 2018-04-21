<?php
/**
 * @var \Ufocms\Frontend\Config $config
 * @var \Ufocms\Frontend\Params $params
 * @var array|null $site
 * @var string $location
 */

//header('HTTP/1.0 302 Moved Temporarily');
header('Location: ' . $location, true, 302);
?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>302 Moved Temporarily</TITLE>
</HEAD><BODY>
<H1>Moved Temporarily</H1>
The document has moved <a href="<?=htmlspecialchars($location)?>">here</a>.<P>
</BODY></HTML>
