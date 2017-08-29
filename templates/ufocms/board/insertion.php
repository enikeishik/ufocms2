<?php
/**
 * @var \Ufocms\Modules\Insertion $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $section
 * @var array|null $item
 * @var array|null $items
 * @var array|null $this->data         insertion settings
 * @var array|null $this->options      insertions call [in template] options
 */
?>
<div class="widgetboard">
<h3><?=$this->data['Title']?></h3>
<?php if (0 < count($items)) { ?>
    <div class="items">
    <?php foreach ($items as $item) { ?>
        <div class="item">
            <div class="date"><?=date('d.m.Y H:i', strtotime($item['DateCreate']));?></div>
            <div class="title"><a href="<?=$section['path']?><?=$item['Id']?>"><?=$item['Title']?></a></div>
            <div class="message"><?=$tools->cutNice(strip_tags($item['Message']), $this->data['ItemsLength'])?>...</div>
            <div class="contacts">Контакты...</div>
        </div>
    <?php } ?>
    </div>
    <div class="all"><a href="<?=$section['path']?>">Все объявления</a></div>
<?php } else { ?>
    <div class="none">Объявлений пока нет</div>
<?php } ?>
</div>
