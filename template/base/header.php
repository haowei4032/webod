<div class="header-wrapper">
    <div class="block-child clear-fix">
        <div class="left-side clear-fix">
            <a class="logo" href="<?=Url::getInstance()->to('/')?>"></a>
            <ul class="menu-list clear-fix">
                <?php foreach(getMenu(1) as $menu): ?>
                    <li><a href="<?=$menu['url']?>"><?=$menu['menu_name']?></a></li>
                <?php endforeach; ?>
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