<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var array|null $items
 * @var int|null $itemsCount
 * @var mixed $actionResult
 */
?>
<pre><?php print_r($actionResult); ?></pre>
<div class="votingsresult">
<h1><?=$this->getSectionTitle()?></h1>
<?php if ($actionResult['voted']) { ?>
    <div>Действие выполнено удачно</div>
<?php } else { ?>
    <div>Действие выполнено неудачно</div>
<?php } ?>
</div>
