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
<div class="oldurlsone">
<h1><?=$item['Title']?></h1>
<div><?=$item['Body']?></div>
<div class="all"><a href="<?=$section['path']?>">Другие материалы раздела «<?=$section['indic']?>»</a></div>
</div>
