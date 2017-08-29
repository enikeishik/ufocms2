<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Core $core
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 */
?>
<div class="usersone">
<div class="section"><?=$section['indic']?></div>
<h1><?=$item['Title']?> (<?=$item['Login']?>)</h1>
<div><b>Логин:</b> <?=$item['Login']?></div>
<div><b>Дата регистрации:</b> <?=$item['DateCreate']?></div>
<div><b>Дата входа:</b> <?=$item['DateLogin']?></div>
<div><?=$item['Description']?></div>
<div><b>Группы:</b> <?=implode(', ', $item['Groups'])?></div>
<div class="all"><a href="<?=$section['path']?>">Все пользователи</a></div>
</div>
