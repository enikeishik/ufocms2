<?php
/**
 * @var \Ufocms\Frontend\Config $config
 * @var \Ufocms\Frontend\Params $params
 * @var array|null $site
 */

header('HTTP/1.0 401 Authorization Required');
?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>401 Authorization Required</TITLE>
</HEAD><BODY>
<H1>Authorization Required</H1>
This server could not verify that you
are authorized to access the document
requested.  Either you supplied the wrong
credentials (e.g., bad password), or your
browser doesn't understand how to supply
the credentials required.<P>
</BODY></HTML>
