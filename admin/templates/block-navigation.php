<?php
$user = $this->core->getUsers()->getCurrent();
$roles = $this->core->getRoles();

$restrictions = $roles->getUserRestrictions($user['Id']);
$hideSections = false;
if (1 == count($restrictions['Sections']) && $roles::RESTRICT_ALL_VALUE == $restrictions['Sections'][0]) {
    $hideSections = true;
}

$coreModules = [];
foreach ($this->config->coreModules as $name => $item) {
    if ($item['Menu'] && !$roles->rolesRestricted($user['Id'], $name)) {
        $coreModules[$name] = $item;
    }
}
?>

<div id="lefttop">
    <div id="opensite"><a href="/<?=$this->config->rootUrl?>?r=<?=time()?>" target="_blank" title="Открыть главную страницу сайта в новом окне">Сайт</a></div>
    <div id="logout"><a href="?<?=$this->config->paramsNames['action']?>=adminlogout&r=<?=time()?>" title="Выход из учетной записи «<?=$user['Login']?>»">Выход</a></div>
</div>

<div class="core"><a href="?"<?php if ((is_null($this->params->sectionId) && is_null($this->params->coreModule)) || 0 === $this->params->sectionId) { echo ' style="color: #cc0000;"'; }?> title="Стартовая страница системы управления сайтом">&#9632;&nbsp;Стартовая&nbsp;страница</a></div>
<div class="core"><a href="?sectionid=-1" style="<?php if (-1 == $this->params->sectionId || ('sections' == $this->params->coreModule && -1 == $this->params->itemId)) { echo ' color: #cc0000;'; }?>padding-left: 7px;" title="Управление главной страницей сайта">&#9650;&nbsp;Главная&nbsp;страница</a></div>

<?php if (!$hideSections) { ?>
<div id="sectionshclop" class="hclop"><div></div></div>
<div id="sections" class="sections"><?php $tree = new \Ufocms\Backend\Tree($this->config, $this->params, $this->core); $tree->render(); ?></div>
<?php } ?>

<?php if (0 < count($coreModules)) { ?>
<div id="coresectionshclop" class="hclop"><div></div></div>
<div id="coresections">
<?php foreach ($coreModules as $name => $item) { ?>
<div class="core"><a href="?<?=$this->config->paramsNames['coreModule']?>=<?=$name?>"<?php if ($name == $this->params->coreModule) { echo ' style="color: #cc0000;"'; }?> title="<?=htmlspecialchars($item['Description'])?>">&#9632;&nbsp;<?=$item['Title']?></a></div>
<?php } ?>
</div>
<?php } ?>
