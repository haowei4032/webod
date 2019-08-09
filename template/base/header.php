<div class="header-wrapper">
    <div class="block-child clear-fix">
        <div class="left-side clear-fix">
            <a class="logo" href="<?=Url::getInstance()->to('/')?>"></a>
            <ul class="menu-list clear-fix">
                <li><a class="current" href="index.php?section=posts">主题</a></li>
                <li><a href="index.php?section=article">文章</a></li>
                <li><a href="index.php?section=picture">图片</a></li>
                <li><a href="index.php?section=share">分享</a></li>
                <li><a href="index.php?section=book">书籍</a></li>
            </ul>
        </div>
        <div class="right-side">
            <form action="search.php"><input type="text" name="q" autocomplete="off" placeholder="搜索" /></form>
            <ul class="menu-list clear-fix">
                <li><a href="index.php?section=register">注册</a></li>
                <li><a href="index.php?section=login">登录</a></li>
            </ul>
        </div>
    </div>
</div>