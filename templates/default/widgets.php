<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array<\Ufocms\Modules\Widget>|null $items
 */
?>
<?php if (0 < count($items)) { ?>
    <div class="widgets">
    <?php foreach ($items as $widget) { ?>
        <?php $widget->render(); ?>
    <?php } ?>
    </div>
<?php } ?>
