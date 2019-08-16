<?php

if (!defined('__SECTION__')) exit('Access Denied');

$id = getRequest()->getInt('id');
$posts = \model\PostsModel::getInstance()->where('posts_id', $id)->first();

?>
<link type="text/css" rel="stylesheet" href="<?=Url::getInstance()->assets('css/index.css')?>"/>
<div class="left-side">
    <?=$posts['posts_title']?>
    <?=$posts['posts_body']?>
    <?=date('Y-m-d h:i:s', $posts['create_time'])?>
</div>
<aside>
    asdfasdfasdf
</aside>