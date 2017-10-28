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
<div class="defaultresult">
<h1><?=$this->getSectionTitle()?></h1>
<?php if ($actionResult) { ?>
    <div>Действие выполнено удачно</div>
<?php } else { ?>
    <div>Действие выполнено неудачно</div>
<?php } ?>
</div>
