<?php

defined('__ROOT__') or define('__ROOT__', __DIR__);
require __ROOT__ . '/vendor/function.php';

//检查提供者
checkProvider();

?>
<!doctype html>
<html lang="zh-cn">
<head>
<title><?=getHash()->getString('seo.title', 'Webod 2.0 预览版')?> - Powered by Webod</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=9">
<meta name="keywords" content="<?=getHash()->get('seo.keywords')?>">
<meta name="description" content="<?=getHash()->get('seo.description')?>">
<link rel="shortcut icon" href="<?=Url::getInstance()->to('/favicon.png')?>" type="image/x-icon">
<link type="text/css" rel="stylesheet" href="<?=Url::getInstance()->assets('css/base.css')?>">
</head>

<body>
<div class="page-wrapper">
    <?=template('base', 'header')?>
    <div class="body-wrapper">
        <div class="block-child"><?=template('base', 'body')?></div>
    </div>
    <div class="footer-wrapper">
        <div class="child-block"><?=template('base', 'footer')?></div>
    </div>
</div>
</body>	
</html>