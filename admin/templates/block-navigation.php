<div class="core"><a href="?"<?php if ((is_null($this->params->sectionId) && is_null($this->params->coreModule)) || 0 === $this->params->sectionId) { echo ' style="color: #cc0000;"'; }?> title="Стартовая страница системы управления сайтом">&#9632;&nbsp;Стартовая&nbsp;страница<sup style="color: #e60;">&nbsp;&beta;&eta;&tau;&alpha;</sup></a></div>
<div class="core"><a href="?sectionid=-1" style="<?php if (-1 == $this->params->sectionId || ('sections' == $this->params->coreModule && -1 == $this->params->itemId)) { echo ' color: #cc0000;'; }?>padding-left: 7px;" title="Управление главной страницей сайта">&#9650;&nbsp;Главная&nbsp;страница</a></div>

<div id="sectionshclop" class="hclop"><div></div></div>
<div id="sections" class="sections"><?php $tree = new \Ufocms\Backend\Tree($this->config, $this->params, $this->core); $tree->render(); ?></div>

<div id="coresectionshclop" class="hclop"><div></div></div>
<div id="coresections">
<?php foreach ($this->config->coreModules as $name => $item) { if (!$item['Menu']) { continue; } ?>
<div class="core"><a href="?<?=$this->config->paramsNames['coreModule']?>=<?=$name?>"<?php if ($name == $this->params->coreModule) { echo ' style="color: #cc0000;"'; }?> title="<?=htmlspecialchars($item['Description'])?>">&#9632;&nbsp;<?=$item['Title']?></a></div>
<?php } ?>
</div>
