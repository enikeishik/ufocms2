<?php
/**
 * @var \Ufocms\Modules\View $this
 * @var \Ufocms\Frontend\Tools $tools
 * @var array|null $site
 * @var array|null $section
 * @var array|null $settings
 * @var array|null $item
 */
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>informer</title>
<meta http-equiv="refresh" content="<?=$settings['UpdateTimeout']?>; URL=?type=iframe&t=<?=time()?>">
<link rel="stylesheet" type="text/css" href="<?=$this->templateUrl?>/css/styles.css">
</head>
<body>
<div style="background-color: #fff; height: 100%;">
    <div class="auctionsitem iframe">
        <?php include_once '_informer.php'; ?>
    </div>
</div>
</body>
</html>
