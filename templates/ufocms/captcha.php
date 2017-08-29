<?php
/**
 * @var \Ufocms\Frontend\Captcha $this
 * @var array $captcha
 */
?>
<input name="<?=$captcha['PostFieldKey']?>" type="hidden" value="<?=$captcha['Ticket']?>">
<img src="/captcha.php?action=image&<?=$captcha['GetFieldKey']?>=<?=$captcha['Ticket']?>&salt=<?=microtime(true)?>" width="120" height="60" alt="Код проверки" align="middle" hspace="5">
<input maxlength="4" size="10" name="<?=$captcha['PostFieldValue']?>" type="text" value=""><br>введите проверочный код<br>
