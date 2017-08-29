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
<div class="widgetdocuments">
    <h3><?=$this->data['Title']?></h3>
    <div>
        <?php if (strlen($item['body']) <= $this->data['ItemsLength']) { ?>
            <?=$item['body']?>
        <?php } else { ?>
            <?=$tools->cutNice(strip_tags($item['body']), $this->data['ItemsLength'])?>
            <div class="more"><a href="<?=$section['path']?>">Читать дальше</a></div>
        <?php } ?>
    </div>
</div>
