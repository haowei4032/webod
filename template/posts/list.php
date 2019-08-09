<?php

if (!defined('__SECTION__')) exit('Access Denied');
$id = getRequest()->getInt('id');

?>

<?php if ($id <> 0): ?>
<?=template('posts', 'detail')?>
<?php else: ?>
<link type="text/css" rel="stylesheet" href="<?=Url::getInstance()->assets('css/index.css')?>"/>
<div class="left-side">
    <div class="topic-block">
        <div class="topic-header"><h3>最新主题</h3></div>
        <div class="topic-list">
            <?php for($i = 1; $i <= 10; $i++): ?>
            <div class="topic-item clear-fix">
                <img class="avatar" src="<?=Url::getInstance()->assets('img/avatar.jpg')?>"/>
                <div class="topic-content">
                    <a href="index.php?section=posts&id=<?=$i?>">打开一个测试页面</a>
                    <div class="topic-meta">
                        <a class="tag" href="/tag/1">测试</a>
                        <a class="author" href="/">小编发布</a>
                        <span class="last-time">30分钟前发布</span>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
        </div>
        <div class="topic-footer">
            <div class="paginate"><?=Ui::Button('&lt;上一页')?><?=Ui::Button('下一页&gt;')?></div>
        </div>
    </div>
</div>
<aside>
    asdfasdfasdf
</aside>
<?php endif; ?>