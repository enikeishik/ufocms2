<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $items
 * @var bool $linkEmpty
 * @var bool $showCount
 * @var string|null $sourcePath
 */

$year = date('Y');
$month = date('m');
$d = getdate(strtotime($year . '-' . $month . '-01'));
$wdCnt = $d['wday'];
$askedDate = '';
if (array_key_exists('date', $moduleParams)) {
    $askedDate = 'Dt' . $moduleParams['date'];
}
$currentDate = 'Dt' . date('Y-m-d');
?>
<style type="text/css">
.widgetnewscalendar { margin: 0px auto; }
.widgetnewscalendar td { text-align: center; }
.widgetnewscalendar td sup { padding: 1px; background-color: #fcc; }
</style>
<div class="widget">
<?php if ($showTitle) { ?>
<h3><?=$title?></h3>
<?php } ?>

<table border="1" cellpadding="3" cellspacing="0" class="widgetnewscalendar">
<tr>
<?php for ($i = 1; $i < $wdCnt; $i++) { //добавляем пустышки, если первый день месяца не понедельник ?>
    <td>&nbsp;</td>
<?php } ?>

<?php for ($i = 1; $i <= 31; $i++) { ?>
    <?php if (!checkdate($month, $i, $year)) { break; } ?>
    <?php $date = 'Dt' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT); ?>
    <?php if ($askedDate == $date) { $highlight1 = ' style="background-color: #e0eeff"'; } else { $highlight1 = ''; } ?>
    <?php if ($currentDate == $date) { $highlight2 = ' style="font-weight: bold"'; } else { $highlight2 = ''; } ?>
    <?php if ($showCount && array_key_exists($date, $items)) { $cnt = '<sup>' . $items[$date]['Cnt'] . '</sup>'; } else { $cnt = ''; } ?>
    
    <?php if ($linkEmpty || array_key_exists($date, $items)) { ?>
    <td<?=$highlight1?>><a href="<?php if (null !== $sourcePath) { ?><?=$sourcePath?><?php } else { ?>/modules/news/<?php } ?><?=$year?>-<?=str_pad($month, 2, '0', STR_PAD_LEFT)?>-<?=str_pad($i, 2, '0', STR_PAD_LEFT)?>"<?=$highlight2?>><?=$i?></a><?=$cnt?></td>
    <?php } else { ?>
    <td<?=$highlight1?>><span<?=$highlight2?>><?=$i?></span></td>
    <?php } ?>
    
    <?php if (7 < ++$wdCnt) { $wdCnt = 1; //если неделя кончилась, закрываем строку ?>
</tr>
<tr>
    <?php } ?>
<?php } ?>

<?php for ($i = $wdCnt; $i <= 7; $i++) { //добавляем пустышки, если последний день месяца не воскресенье ?>
    <td>&nbsp;</td>
<?php } ?>
</tr>
</table>

</div>
