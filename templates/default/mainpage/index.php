<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $item
 * @var array|null $items
 */
?>
<h1><?=$this->getSectionTitle()?></h1>
<?=$item['body']?>
<table border="0" cellpadding="0" cellspacing="0" id="mpcontenttable" width="100%"><tr valign="top">
<td id="mpcontenttableleft">
    <?php $this->renderInsertions(['PlaceId' => 3]); ?>
</td>
<td id="mpcontenttableseparator">&nbsp;&nbsp;</td>
<td id="mpcontenttableright">
    <?php $this->renderInsertions(['PlaceId' => 4]); ?>
</td>
</tr></table><br>
