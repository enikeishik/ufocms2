<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 */

$message = strip_tags($item['UMessage']);
?>
<div class="faqone">
    <div class="section"><?=$section['indic']?></div>
    <dl>
        <dt>
            <?=date('d.m.Y H:i', strtotime($item['DateCreate']));?>
            <?php if (100 < strlen($message)) { ?>
                <h1><?=$tools->cutNice($message, 100)?>...</h1>
                <div><?=$message?></div>
            <?php } else { ?>
                <h1><?=$message?></h1>
            <?php } ?>
            <div>
            <?=strip_tags($item['USign'])?>
            |
            <?=strip_tags($item['UEmail'])?>
            |
            <?=strip_tags($item['UUrl'])?>
            </div>
        </dt>
        <dd>
            <div><?=strip_tags($item['AMessage'], '<br>')?></div>
            <div>
            <?=date('d.m.Y H:i', strtotime($item['DateAnswer']));?>
            |
            <?=strip_tags($item['ASign'])?>
            |
            <?=strip_tags($item['AEmail'])?>
            |
            <?=strip_tags($item['AUrl'])?>
            </div>
        </dd>
    </dl>
    <div class="all"><a href="<?=$section['path']?>">Другие вопросы</a></div>
</div>
