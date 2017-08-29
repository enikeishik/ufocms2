<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 */
?>
<div class="boardone">
    <div class="section"><?=$section['indic']?></div>
    <dl>
        <dt>
            <?=date('d.m.Y H:i', strtotime($item['DateCreate']));?>
            <h1><?=strip_tags($item['Title']);?></h1>
        </dt>
        <dd>
            <?=strip_tags($item['Message'], '<br>')?>
            <blockquote><?=strip_tags($item['Contacts'], '<br>')?></blockquote>
        </dd>
    </dl>
    <div class="all"><a href="<?=$section['path']?>">Другие объявления</a></div>
</div>
