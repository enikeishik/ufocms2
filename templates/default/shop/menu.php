<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 */
?>
<div class="shopmenu">
    <?php if (!$this->moduleParams['isRoot']) { ?><div class="shop"><a href="<?=$section['path']?>"><?=$section['indic']?></a></div><?php } ?>
    <?php if ('show' != $this->moduleParams['order']) { ?><div><a href="<?=$section['path']?>?order=show&rnd=<?=microtime(true)?>">Корзина</a></div><?php } ?>
    <?php if ('show' != $this->moduleParams['cabinet']) { ?><div><a href="<?=$section['path']?>?cabinet=show&rnd=<?=microtime(true)?>">Кабинет</a></div><?php } ?>
</div>
