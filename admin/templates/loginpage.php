<?php require_once 'layout-head.php'; ?>
<body>
<?php if (isset($this->model) && !is_null($result = $this->model->getResult())) { ?>
<div class="result"><?=$result?><span class="close" title="Скрыть" onclick="this.parentNode.style.display='none'">X</span></div>
<?php } ?>

<style>
table { margin: 20px auto; }
</style>

<form method="post" action="?<?=$this->config->paramsNames['action']?>=adminlogin&rnd=<?=time()?>">
<table>
<tr><td>Логин</td><td><input type="text" name="login" value="" placeholder="login" required></td></tr>
<tr><td>Пароль</td><td><input type="password" name="password" value="" placeholder="****" required></td></tr>
<tr><th colspan="2"><input type="submit" value="вход"></th></tr>
</table>
</form>

<?php require_once 'layout-end.php'; ?>