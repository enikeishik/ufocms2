<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var itemsay $items
 */

function dayPart($tod)
{
    switch ($tod) {
        case 0: return 'ночь';
        case 1: return 'утро';
        case 2: return 'день';
        case 3: return 'вечер';
        default: return '';
    }
}
function cloudiness($cloudiness)
{
    switch ($cloudiness) {
        case 0: return 'ясно';
        case 1: return 'малооблачно';
        case 2: return 'облачно';
        case 3: return 'пасмурно';
        default: return '';
    }
}

function precipitation($precipitation)
{
    switch ($precipitation) {
        case 0:
        case 1:
        case 2:
        case 3:
        case 9:
            return '';
        case 4: return 'дождь';
        case 5: return 'ливень';
        case 6:
        case 7: 
            return 'снег';
        case 8: return 'гроза';
        case 10: return 'без осадков';
        default: return '';
    }
}

function month($month)
{
    switch ($month) {
        case 1:  return 'января';
        case 2:  return 'февраля';
        case 3:  return 'марта';
        case 4: return 'апреля';
        case 5:  return 'мая';
        case 6:  return 'июня';
        case 7:  return 'июля';
        case 8: return 'августа';
        case 9:  return 'сентября';
        case 10: return 'октября';
        case 11: return 'ноября';
        case 12: return 'декабря';
        default: return $month;
    }
}

?>
<div class="widget">
<?php if ($showTitle) { ?>
    <h3><?=$title?></h3>
<?php } ?>

<?php if (is_array($items) && 0 != count($items)) { ?>
    <div><?=$items['TOWN']['sname']?></div>
    <dl class="weather">
    <dt><?=$items[0]['FORECASTday']?> <?=month($items[0]['FORECASTmonth'])?>
        <?=dayPart($items[0]['FORECASTtod'])?>:</dt><dd>
        <?=cloudiness($items[0]['PHENOMENAcloudiness'])?>, 
        <?=precipitation($items[0]['PHENOMENAprecipitation'])?>,<br>
        давление: <?=$items[0]['PRESSUREmin']?>-<?=$items[0]['PRESSUREmax']?> мм рт.ст.,<br>
        отн. влажность: <?=$items[0]['RELWETmin']?>-<?=$items[0]['RELWETmax']?>%,<br>
        <?=$items[0]['TEMPERATUREmin']?>..<?=$items[0]['TEMPERATUREmax']?><sup>o</sup>C</dd>
    <dt><?=$items[1]['FORECASTday']?> <?=month($items[1]['FORECASTmonth'])?>
        <?=dayPart($items[1]['FORECASTtod'])?>:</dt><dd>
        <?=cloudiness($items[1]['PHENOMENAcloudiness'])?>, 
        <?=precipitation($items[1]['PHENOMENAprecipitation'])?>,<br>
        давление: <?=$items[1]['PRESSUREmin']?>-<?=$items[1]['PRESSUREmax']?>мм рт.ст.,<br>
        отн. влажность: <?=$items[1]['RELWETmin']?>-<?=$items[1]['RELWETmax']?>%,<br>
        <?=$items[1]['TEMPERATUREmin']?>..<?=$items[1]['TEMPERATUREmax']?><sup>o</sup>C</dd>
    <dt><?=$items[2]['FORECASTday']?> <?=month($items[2]['FORECASTmonth'])?>
        <?=dayPart($items[2]['FORECASTtod'])?>:</dt><dd>
        <?=cloudiness($items[2]['PHENOMENAcloudiness'])?>, 
        <?=precipitation($items[2]['PHENOMENAprecipitation'])?>,<br>
        давление: <?=$items[2]['PRESSUREmin']?>-<?=$items[2]['PRESSUREmax']?>мм рт.ст.,<br>
        отн. влажность: <?=$items[2]['RELWETmin']?>-<?=$items[2]['RELWETmax']?>%,<br>
        <?=$items[2]['TEMPERATUREmin']?>..<?=$items[2]['TEMPERATUREmax']?><sup>o</sup>C</dd>
    <dt><?=$items[3]['FORECASTday']?> <?=month($items[3]['FORECASTmonth'])?>
        <?=dayPart($items[3]['FORECASTtod'])?>:</dt><dd>
        <?=cloudiness($items[3]['PHENOMENAcloudiness'])?>, 
        <?=precipitation($items[3]['PHENOMENAprecipitation'])?>,<br>
        давление: <?=$items[3]['PRESSUREmin']?>-<?=$items[3]['PRESSUREmax']?>мм рт.ст.,<br>
        отн. влажность: <?=$items[3]['RELWETmin']?>-<?=$items[3]['RELWETmax']?>%,<br>
        <?=$items[3]['TEMPERATUREmin']?>..<?=$items[3]['TEMPERATUREmax']?><sup>o</sup>C</dd>
    </dl>
    
<?php } else { ?>
    
    <p>Данные недоступны.</p>
    
<?php } ?>
</div>
