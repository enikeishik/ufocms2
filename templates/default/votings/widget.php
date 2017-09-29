<?php
/**
 * @var \Ufocms\Modules\Widget $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array $moduleParams
 * @var bool $showTitle
 * @var string $title
 * @var string $content
 * @var array $item
 * @var string $ticket
 * @var bool $showForm
 * @var bool $showResults
 */
?>
<div class="widget">
    <?php if ($showTitle) { ?>
    <h3><?=$title?></h3>
    <?php } ?>
    <div class="widgetvotings">
        <div class="item">
            <div class="date">
                <span class="start">Начало <?=date('d.m.Y H:i', strtotime($item['DateStart']))?></span>
                <span class="stop">Окончание <?=date('d.m.Y H:i', strtotime($item['DateStop']))?></span>
            </div>
            <div class="clear"></div>
            <h4><?=$item['Title']?></h4>
            
            <?php if ('' != $item['Image']) { ?>
                <div class="image"><?=$item['Image']?></div>
            <?php } ?>
            
            <?php if ($showForm) { ?>
                <div class="votingsanswerstogether">
                <form action="<?=$item['path']?><?=$item['Id']?>?action=vote&t=<?=time()?>" method="post">
                <input type="hidden" name="ticket" value="<?=$ticket?>">
                <?php foreach ($item['Answers'] as $answer) { ?>
                    <div class="votingsanswer"><label><input type="radio" name="answer" value="<?=$answer['Id']?>"><?=$answer['Title']?></label></div>
                <?php } ?>
                <?php if ($item['CheckCaptcha']) { ?>
                    <div class="votingscaptcha"><?php $tools->getCaptcha()->show(); ?></div>
                <?php } ?>
                <div class="votingssubmit"><input type="submit" value="Проголосовать"></div>
                </form>
                </div>
                
                <?php if (-1 == $item['ResultsDisplay']) { ?>
                    <div class="votingsshowresults"><a href="<?=$item['path']?><?=$item['Id']?>/results">Посмотреть результаты</a></div>
                <?php } ?>
            <?php } else if ($showResults) { ?>
                <div class="votingsresults">
                <?php foreach ($item['Answers'] as $answer) { ?>
                    <?php $percent = $item['VotesCnt'] ? round($answer['VotesCnt']/$item['VotesCnt']*100) : 0; ?>
                    <div class="votingsanswer">
                        <span class="label"><?=$answer['Title']?></span>
                        <span class="graph" style="width: <?=$percent*2?>px;"><span></span></span>
                        <span class="value"><?=$percent?>% (<?=$answer['VotesCnt']?>)</span>
                    </div>
                <?php } ?>
                </div>
            <?php } else { ?>
                <div class="votingsresults">
                    <p>Результаты будут опубликованы после окончания голосования.</p>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
