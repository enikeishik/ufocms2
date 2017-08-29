<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var array|null $items
 * @var mixed $actionResult
 */
?>
<div class="shoporderresult">

<?php include_once 'menu.php'; ?>

<h1>Заказ</h1>

<?php if (!$actionResult['request']) { ?>
    <p>Некорректный запрос.</p>

<?php } else if ('add' == $actionResult['action']) { ?>
    <?php if ($actionResult['result']) { ?>
    <p>Позиция добавлена в заказ.</p>
    <?php } else { ?>
    <p>При добавлении позиции в заказ возникли ошибки, свяжитесь пожалуйста с администрацией сайта.</p>
    <?php } ?>

<?php } else if ('remove' == $actionResult['action']) { ?>
    <?php if ($actionResult['result']) { ?>
    <p>Позиция удалена из заказа.</p>
    <?php } else { ?>
    <p>При удалении позиции из заказа возникли ошибки, свяжитесь пожалуйста с администрацией сайта.</p>
    <?php } ?>

<?php } else if ('clear' == $actionResult['action']) { ?>
    <?php if ($actionResult['result']) { ?>
    <p>Заказ удален.</p>
    <?php } else { ?>
    <p>При удалении заказа возникли ошибки, свяжитесь пожалуйста с администрацией сайта.</p>
    <?php } ?>

<?php } else if ('confirm' == $actionResult['action']) { ?>
    <?php if ($actionResult['result']) { ?>
    <form action="<?php echo $section['path']; ?>action?order=send&rnd=<?php echo microtime(true); ?>" method="post">
    <table>
    <tr><th>Адрес</th><td><?=htmlspecialchars($item['Address'])?></td></tr>
    <tr><th>Email</th><td><?=htmlspecialchars($item['Email'])?></td></tr>
    <tr><th>Телефон</th><td><?=htmlspecialchars($item['Phone'])?></td></tr>
    <tr><th>Комментарий</th><td><?=htmlspecialchars($item['Comment'])?></td></tr>
    <tr><td colspan="2" style="text-align: center;">
        <input type="button" onclick="history.back()" value="Назад">
        <input type="submit" value="Отправить">
    </td></tr>
    </table>
    </form>
    <?php } else { ?>
    <p>Не удалось сформировать заказ, свяжитесь пожалуйста с администрацией сайта.</p>
    <?php } ?>


<?php } else if ('send' == $actionResult['action']) { ?>
    <?php if ($actionResult['result']) { ?>
    <script type="text/javascript">setTimeout('location.href="<?php echo $section['path']; ?>action?order=sended&rnd=<?php echo microtime(true); ?>"', 2000);</script>
    <p>Отправка заказа...</p>
    <?php } else { ?>
    <p>Не удалось отправить заказ, свяжитесь пожалуйста с администрацией сайта.</p>
    <?php } ?>

<?php } else if ('sended' == $actionResult['action']) { ?>
    <p>Заказ отправлен.</p>

<?php } ?>

</div>
