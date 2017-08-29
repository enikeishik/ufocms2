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
<h1><?=$section['indic'];?></h1>
<p><?=$item['Body'];?></p>
<?php if ($section['shcomments']) { ?>
    <?php $this->renderComments(); ?>
<?php } ?>
