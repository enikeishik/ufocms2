<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var int $duration
 * @var array $items
 */

$width = 400;
$height = 300;
?>
<style type="text/css">
.jcarousel-wrapper {
    margin: 20px auto;
    position: relative;
    border: 10px solid #fff;
    width: <?=$width?>px;
    height: <?=($height+30)?>px;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 0 2px #999;
       -moz-box-shadow: 0 0 2px #999;
            box-shadow: 0 0 2px #999;
}
.jcarousel { position: relative; overflow: hidden; width: 100%; height: 100%; }
.jcarousel ul { width: 20000em; position: relative; list-style: none; margin: 0; padding: 0; }
.jcarousel li { float: left; }
.jcarousel-control-prev, .jcarousel-control-prev:link, 
.jcarousel-control-next, .jcarousel-control-next:link {
    position: absolute;
    top: <?=($height+5)?>px;
    width: 30px;
    height: 30px;
    text-align: center;
    background: #999;
    color: #fff;
    text-decoration: none;
    text-shadow: 0 0 1px #000;
    font: 24px/27px Arial, sans-serif;
    -webkit-border-radius: 30px;
       -moz-border-radius: 30px;
            border-radius: 30px;
    -webkit-box-shadow: 0 0 2px #999;
       -moz-box-shadow: 0 0 2px #999;
            box-shadow: 0 0 2px #999;
}
.jcarousel-control-prev { left: <?=($width/2 - 50)?>px; }
.jcarousel-control-next { right: <?=($width/2 - 50)?>px; }
.jcarousel-control-prev:hover span, .jcarousel-control-next:hover span { display: block; }
.jcarousel-control-prev.inactive, .jcarousel-control-next.inactive { opacity: .5; cursor: default; }
</style>
<script type="text/javascript" src="/templates/ufocms/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/templates/ufocms/js/jquery.jcarousel-core.min.js"></script>
<div class="widget">
<?php if ($showTitle) { ?>
    <h3><?=$title?></h3>
<?php } ?>
<?php if (0 < count($items)) { ?>
    <div class="jcarousel-wrapper">
        <div class="jcarousel">
            <ul>
                <?php foreach ($items as $item) { ?>
                <li><a href="<?=$item?>" target="slideshow"><img src="<?=$item?>" alt="" style="width: <?=$width?>px; height: <?=$height?>px;"></a></li>
                <?php } ?>
            </ul>
        </div>
        <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
        <a href="#" class="jcarousel-control-next">&rsaquo;</a>
    </div>
    <div class="clear"></div>
    <script type="text/javascript">
    (function($) {
        $(function() {
            $('.jcarousel')
                .jcarousel({
                    wrap: 'circular'
                })
                .jcarouselAutoscroll({
                    interval: 1000 * <?=$duration?>,
                    target: '+=1',
                    autostart: true
                });
            $('.jcarousel-control-prev')
                .on('jcarouselcontrol:active', function() {
                    $(this).removeClass('inactive');
                })
                .on('jcarouselcontrol:inactive', function() {
                    $(this).addClass('inactive');
                })
                .jcarouselControl({
                    target: '-=1'
                });
            $('.jcarousel-control-next')
                .on('jcarouselcontrol:active', function() {
                    $(this).removeClass('inactive');
                })
                .on('jcarouselcontrol:inactive', function() {
                    $(this).addClass('inactive');
                })
                .jcarouselControl({
                    target: '+=1'
                });
        });
    })(jQuery);
    </script>
<?php } ?>
</div>
