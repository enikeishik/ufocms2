<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $items
 * @var array|null $item
 * @var int|null $itemsCount
 * @var array|null $rating
 * @var string $path
 * @var array|null $options
 */


//форма реализована посредством JavaScript не только 
//чтобы не перезагружать страницу, но и чтобы случайно не учитывать ботов
?>
<div class="rateform">
<style type="text/css">
.rate-button { 
    border: 0px; width: 26px; height: 24px; 
    color: #ffff00; font-family: Arial; font-size: 10px; font-weight: bold; 
    cursor: pointer; 
}
</style>
<script type="text/javascript">
document.write('<form action="?rnd=<?=time()?>#rating" method="post" id="rateForm" onsubmit="return sendRatingForm(this)">оценить: ' + 
               '<input type="submit" class="rate-button" style="background: url(/templates/default/images/rate-1.gif);" name="rate" value="1" title="1" onclick="setRateValue(this.value)">' + 
               '<input type="submit" class="rate-button" style="background: url(/templates/default/images/rate-2.gif)" name="rate" value="2" title="2" onclick="setRateValue(this.value)">' + 
               '<input type="submit" class="rate-button" style="background: url(/templates/default/images/rate-3.gif)" name="rate" value="3" title="3" onclick="setRateValue(this.value)">' + 
               '<input type="submit" class="rate-button" style="background: url(/templates/default/images/rate-4.gif)" name="rate" value="4" title="4" onclick="setRateValue(this.value)">' + 
               '<input type="submit" class="rate-button" style="background: url(/templates/default/images/rate-5.gif)" name="rate" value="5" title="5" onclick="setRateValue(this.value)">' + 
               '</form>');
</script>
<noscript>ƒл¤ оценки материала необходимо включить в броузере поддержку JavaScript</noscript>
</div>
