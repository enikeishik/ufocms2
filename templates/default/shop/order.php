<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var array|null $items
 */
?>
<div class="shoporder">

<?php include_once 'menu.php'; ?>

<h1>Заказ</h1>

<?php if (C_DEBUG) { ?>
    <h2>Информация о заказе</h2>
    <?php foreach ($item as $key => $val) { ?>
    <div><b><?php echo $key; ?></b> <?php echo $val; ?></div>
    <?php } ?>
<?php } ?>

<?php if (null === $items || 0 == count($items)) { ?>
    <p>Еще ничего не заказано.</p>
<?php } else { ?>
    <h2>Список заказа</h2>
    <table>
    <tr>
    <th align="left">Категория</th>
    <th align="left">Товар</th>
    <th align="right">Количество</th>
    </tr>
    <?php if (null !== $items) { foreach ($items as $item) { ?>
    <tr>
    <?php if ($item['Title']) { ?>
    <td><a href="<?php echo $section['path']; ?><?php echo $item['CategoryAlias']; ?>"><?php echo $item['CategoryTitle']; ?></a></td>
    <td><a href="<?php echo $section['path']; ?><?php echo $item['CategoryAlias']; ?>/<?php echo $item['Alias']; ?>"><?php echo $item['Title']; ?></a></td>
    <?php } else { ?>
    <td>товар удален</td>
    <td>товар удален</td>
    <?php } ?>
    <td align="right" width="140">
        <?php echo $item['ItemsCount']; ?>
        <?php if ($item['Title']) { ?>
        <span class="button">
            <a href="<?php echo $section['path']; ?>action/?order=add&id=<?php echo $item['Id']; ?>&rnd=<?php echo microtime(true); ?>" title="Увеличить количество на 1">+</a>
        </span>
        <span class="button">
            <a href="<?php echo $section['path']; ?>action/?order=remove&id=<?php echo $item['Id']; ?>&rnd=<?php echo microtime(true); ?>" title="Уменьшить количество на 1">-</a>
        </span>
        <?php } ?>
    </td>
    </tr>
    <?php } } ?>
    </table>

    <div class="manage">
        <span class="button">
            <a href="<?php echo $section['path']; ?>action/?order=clear" onclick="return confirm('Вы действительно хотите удалить все позиции из заказа?')">Очистить заказ</a>
        </span>
        <span class="button">
            <a href="<?php echo $section['path']; ?>action/?order=form&rnd=<?php echo microtime(true); ?>">Оформить заказ</a>
        </span>
    </div>
<?php } ?>

<div class="all"><a href="<?=$section['path']?>">Вернуться к покупкам</a></div>

</div>
