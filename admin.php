<?php

defined('__ROOT__') or define('__ROOT__', __DIR__);
defined('__SECTION__') or define('__SECTION__', 1);
require __ROOT__ . '/vendor/function.php';

//检查提供者
checkProvider();

$section = getRequest()->getString('section', 'index');

?>
<!doctype html>
<html lang="zh-cn">
<head>
<title><?=getHash()->getString('seo.title')?> - 后台管理 - Powered by Webod</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=9">
<meta name="keywords" content="<?=getHash()->get('seo.keywords')?>">
<meta name="description" content="<?=getHash()->get('seo.description')?>">
<link rel="shortcut icon" href="<?=Url::getInstance()->to('/favicon.ico')?>" type="image/x-icon">
<link type="text/css" rel="stylesheet" href="<?=Url::getInstance()->assets('css/admin.css')?>">
<?=Ui::css('//at.alicdn.com/t/font_1321177_37ljb6iqnko.css')?>
</head>

<body>
<div class="page-wrapper">
    <div class="left-side">
        <img src="<?=Url::getInstance()->assets('img/logo_dark.png')?>" style="height: 40px; object-fit: cover; display: block; margin: 5px 10px;"/>
        <ul class="menu-list">
            <?php foreach([
                ['key' => 'index', 'url' => 'admin.php?section=index', 'label' => '首页'],
                ['key' => 'posts', 'url' => 'admin.php?section=posts', 'label' => '帖子'],
                ['key' => 'category', 'url' => 'admin.php?section=category', 'label' => '分类'],
                ['key' => 'page', 'url' => 'admin.php?section=page', 'label' => '页面'],
                ['key' => 'user', 'url' => 'admin.php?section=user', 'label' => '用户'],
                ['key' => 'safe', 'url' => 'admin.php?section=safe', 'label' => '安全'],
                ['key' => 'apps', 'url' => 'admin.php?section=apps', 'label' => '应用中心'],
                ['key' => 'setting', 'url' => 'admin.php?section=setting', 'label' => '设置'],
            ] as $item): ?>
                <li><a href="<?=$item['url']?>"<?=$section === $item['key'] ? ' class="active"' : ''?>><?php if ($item['key'] === 'apps'): ?><svg t="1564385229969" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" width="15" height="15" style="display:block; float: left; margin-top: 12px; margin-right: 10px;"><path d="M40.96 291.84l437.76 186.88v504.32c-35.84-15.36-71.68-33.28-107.52-48.64-33.28-15.36-69.12-30.72-110.08-48.64L138.24 832c-15.36-7.68-28.16-15.36-40.96-28.16C87.04 793.6 76.8 780.8 69.12 768c-7.68-12.8-15.36-28.16-20.48-43.52-5.12-15.36-7.68-30.72-7.68-43.52V291.84z" fill="#BBBBBB" p-id="1338"></path><path d="M983.04 291.84v389.12c0 17.92-2.56 35.84-10.24 51.2-7.68 17.92-15.36 33.28-25.6 46.08-10.24 15.36-23.04 28.16-35.84 38.4-12.8 10.24-25.6 20.48-38.4 25.6-35.84 15.36-71.68 30.72-112.64 46.08-38.4 15.36-74.24 30.72-107.52 46.08-38.4 15.36-76.8 33.28-112.64 46.08V478.72l442.88-186.88z" fill="#00D19C" p-id="1339"></path><path d="M954.88 215.04L517.12 396.8 76.8 215.04 514.56 40.96z" fill="#FCA253" p-id="1340"></path></svg><?php else: ?><icon class="icon-<?=$item['key']?>"></icon><?php endif; ?><?=$item['label']?></a></li>
            <?php endforeach; ?>

        </ul>
    </div>
    <div class="main-side">
        <div class="main-side-wrapper">
            <div class="main-side-header">dddddd</div>
            <div class="main-side-page">
                <?=template('admin', $section)?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
