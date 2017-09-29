<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 * @var string $ticket
 * @var bool $showForm
 * @var bool $showResults
 */
?>
<div class="votingsitem">
    <div class="section"><?=$section['indic']?></div>
    
    <?php if ($item['IsClosed']) { ?>
    <div class="closed">Голосование завершено</div>
    <div class="clear"></div>
    <?php } ?>
    <div class="date">
        <span class="start">Начало <?=date('d.m.Y H:i', strtotime($item['DateStart']))?></span>
        <span class="stop">Окончание <?=date('d.m.Y H:i', strtotime($item['DateStop']))?></span>
    </div>
    <div class="clear"></div>
    
    <h1><?=$item['Title']?></h1>
    
    <?php if ('' != $item['Image']) { ?>
        <div class="image"><?=$item['Image']?></div>
    <?php } ?>
    
    <div class="info">
    <?=$item['Description']?>
    </div>
    
    <?php if ($showForm) { ?>
        <?php if (!$item['AnswersSeparate']) { ?>
            <div class="votingsanswerstogether">
            <form action="?action=vote&t=<?=time()?>" method="post">
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
        <?php } else { ?>
            <div class="votingsanswersseparate">
            <?php foreach ($item['Answers'] as $answer) { ?>
                <div class="votingsanswer">
                <form action="?action=vote&t=<?=time()?>" method="post">
                <input type="hidden" name="ticket" value="<?=$ticket?>">
                <input type="hidden" name="answer" value="<?=$answer['Id']?>">
                <h2 class="votingsanswertitle"><?=$answer['Title']?></h2>
                <?php if ('' != $answer['Image']) { ?>
                    <div class="image"><?=$answer['Image']?></div>
                <?php } ?>
                <div class="votingsanswerinfo"><?=$answer['Description']?></div>
                <?php if ($item['CheckCaptcha']) { ?>
                    <div class="votingscaptcha"><?php $tools->getCaptcha()->show(); ?></div>
                <?php } ?>
                <div class="votingssubmit"><input type="submit" value="Проголосовать"></div>
                </form>
                </div>
            <?php } ?>
            </div>
        <?php } ?>
        <?php if (-1 == $item['ResultsDisplay']) { ?>
            <div class="votingsshowresults"><a href="<?=$section['path']?><?=$item['Id']?>/results">Посмотреть результаты</a></div>
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

    <div class="all"><a href="<?=$section['path']?>">Другие голосования</a></div>
</div>
