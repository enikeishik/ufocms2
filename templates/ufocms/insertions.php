<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array<\Ufocms\Modules\Insertion>|null $items
 */
?>
<?php if (0 < count($items)) { ?>
    <div class="widgets">
    <?php foreach ($items as $insertion) { ?>
        <div class="widget"><?php $insertion->render(); ?></div>
    <?php } ?>
    </div>
<?php } ?>
