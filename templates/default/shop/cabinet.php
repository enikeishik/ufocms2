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
<div class="shopcabinet">

<?php include_once 'menu.php'; ?>

<h1>Кабинет зарегистрированного пользователя</h1>

<?php if (0 < count($items)) { ?>

<h2>История заказов</h2>
<?php foreach ($items as $item) { ?>
<div class="order">
    <h3>Информация о заказе (id: <?=$item['Id']?>)</h3>
    <table class="info">
    <tr><th>Дата оформления</th><td><?php echo $item['DateCreate']; ?></td></tr>
    <tr><th>Текущий статус</th><td><?php echo $item['StatusTitle']; ?></td></tr>
    <?php if (1 < $item['Status']) { ?>
    <tr><th>Дата статуса</th><td><?php echo $item['StatusDate']; ?></td></tr>
    <?php } ?>
    <tr><th>Адрес доставки</th><td><?php echo $item['Address']; ?></td></tr>
    <tr><th>Контактный телефон</th><td><?php echo $item['Email']; ?></td></tr>
    <tr><th>Email</th><td><?php echo $item['Phone']; ?></td></tr>
    <tr><th>Комментарий</th><td><?php echo $item['Comment']; ?></td></tr>
    </table>
    <h3>Список заказа</h3>
    <table class="elements">
    <tr>
    <th align="left">Категория</th>
    <th align="left">Товар</th>
    <th align="right">Количество</th>
    </tr>
    <?php foreach ($item['Elements'] as $element) { ?>
    <tr>
    <?php if ($element['Title']) { ?>
    <td><a href="<?php echo $section['path']; ?><?php echo $element['CategoryAlias']; ?>"><?php echo $element['CategoryTitle']; ?></a></td>
    <td><a href="<?php echo $section['path']; ?><?php echo $element['CategoryAlias']; ?>/<?php echo $element['Alias']; ?>"><?php echo $element['Title']; ?></a> (id: <?=$element['Id']?>)</td>
    <?php } else { ?>
    <td>товар удален</td>
    <td>товар удален</td>
    <?php } ?>
    <td align="right" width="140"><?php echo $element['ItemsCount']; ?></td>
    </tr>
    <?php } ?>
    </table>
    <?php if ($item['Report']) { ?>
    <h3>Отчет/уведомление</h3>
    <div><?=$item['Report']?></div>
    <?php } ?>
</div>
<?php } ?>

<?php } else { ?>

<p>Заказов пока нет.</p>

<?php } ?>

</div>
