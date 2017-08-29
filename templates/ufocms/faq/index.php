<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $items
 * @var int|null $itemsCount
 */
?>
<div class="faq">
<h1><?=$section['indic']?></h1>
<?=$settings['BodyHead']?>
<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <dl>
            <dt>
                <?=date('d.m.Y H:i', strtotime($item['DateCreate']));?>
                |
                <?=strip_tags($item['USign'])?>
                |
                <?=strip_tags($item['UEmail'])?>
                |
                <?=strip_tags($item['UUrl'])?>
            </dt>
            <dd>
                <?php if (0 < strlen($item['AMessage'])) { ?>
                    <a href="<?=$section['path']?><?=$item['Id']?>"><?=strip_tags($item['UMessage'], '<br>')?></a>
                    <blockquote><?=strip_tags($item['AMessage'], '<br>')?></blockquote>
                <?php } else { ?>
                    <?=strip_tags($item['UMessage'], '<br>')?>
                <?php } ?>
            </dd>
        </dl>
    <?php } ?>
    </div>
    <?php if ($this->params->pageSize < $itemsCount) { ?>
        <div class="pagination"><?php $this->renderPagination(); ?></div>
    <?php } ?>
<?php } else { ?>
    <div class="none">Вопросов пока нет</div>
<?php } ?>
<?php include 'form.php' ?>
<?=$settings['BodyFoot']?>
</div>
