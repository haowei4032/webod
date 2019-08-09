<?php

defined('__ROOT__') or define('__ROOT__', __DIR__);
require __ROOT__ . '/vendor/function.php';

function getBuildInfo()
{
    return BuildConfig::VERSION_CODE . '_' . BuildConfig::VERSION . '_' . BuildConfig::VERSION_LANGUAGE . '_build_' . BuildConfig::BUILD_DATE;
}

addJsonResponse('action=prepareInstall', function ($request) {

    $link = mysqli_init();
    set_error_handler(function ($error) {
    });
    mysqli_real_connect($link, $request['db_host'], $request['db_user'], $request['db_password']);
    restore_error_handler();

    return [
        'code' => mysqli_connect_errno() ? 500 : 0,
        'message' => ''
    ];

});

$step = getRequest()->getInt('step', $defaultValue = 1, $minValue = 1);

?>
<!doctype html>
<html lang="zh-cn">
<head>
<title>Webod 2.0 预览版 - 安装向导 - Powered by Webod</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=9">
<link rel="shortcut icon" href="<?= Url::getInstance()->to('/favicon.ico') ?>" type="image/x-icon">
<link type="text/css" rel="stylesheet" href="<?= Url::getInstance()->assets('css/base.css') ?>">
<link type="text/css" rel="stylesheet" href="<?= Url::getInstance()->assets('css/install.css') ?>">
</head>

<body>
<div class="frameset">
    <div class="header clear-fix">
        <span class="logo"></span>
        <span class="text"><small title="预览版">Preview</small>安装向导</span>
        <span class="build">版本号： <?= getBuildInfo() ?></span>
    </div>
    <div class="steps-bar">
        <div class="item<?= $step > 1 ? ' finish' : ' current' ?>">
            <div class="item-icon"><em>1</em></div>
            <div class="item-text">安装许可协议</div>
        </div>
        <div class="item<?= $step > 2 ? ' finish' : ($step == 2 ? ' current' : ' wait') ?>">
            <div class="item-icon"><em>2</em></div>
            <div class="item-text">检查安装环境</div>
        </div>
        <div class="item<?= $step > 3 ? ' finish' : ($step == 3 ? ' current' : ' wait') ?>">
            <div class="item-icon"><em>3</em></div>
            <div class="item-text">初始化数据</div>
        </div>
        <div class="item<?= $step > 4 ? ' finish' : ($step == 4 ? ' current' : ' wait') ?>">
            <div class="item-icon"><em>4</em></div>
            <div class="item-text">完成</div>
        </div>
    </div>
    <div class="main">
        <?php if ($step === 1): ?>
            <div class="i-agreement">
                <h3>请仔细阅读此安装协议</h3>
                <div class="content">
                    <center>
                        <h2>中文版授权协议 适用于中文用户</h2>
                    </center>
                    <p>版权所有 (c) 2010-2019，好为科技有限公司保留所有权利。</p>
                    <p>感谢您选择 Webod UTF8版 - 轻博客产品。希望我们的努力能为您提供一个高效快速和强大的开源平台解决方案。</p>
                    <p>Webod 英文全称为 Haowei Web Board，中文全称为 Webod 多元化言论系统，以下简称 Webod。</p>
                    <p>好为科技有限公司为 Webod 产品的开发商，依法独立拥有 Webod 产品著作权（中国国家版权局著作权登记号 2019000000 http://haowei.tech，Webod
                        官方网站网址为 http://webod.cn，Webod 官方讨论区网址为 http://webod.cn。</p>
                    <p>Webod
                        著作权已在中华人民共和国国家版权局注册，著作权受到法律和国际公约保护。使用者：无论个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用
                        Webod 软件。</p>
                    <p>本授权协议适用且仅适用于 Webod 版本，好为科技有限公司拥有对本授权协议的最终解释权。</p>
                    <h3>I. 协议许可的权利</h3>
                    <ol>
                        <li>您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用。</li>
                        <li>您可以在协议规定的约束和限制范围内修改 Webod 源代码(如果被提供的话)或界面风格以适应您的网站要求。</li>
                        <li>您拥有使用本软件构建的个人博客中全部会员资料、文章及相关信息的所有权，并独立承担与文章内容的相关法律义务。</li>
                        <li>获得商业授权之后，您可以将本软件应用于商业用途，同时依据所购买的授权类型中确定的技术支持期限、技术支持方式和技术
                            支持内容，自购买时刻起，在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首 要考虑，但没有一定被采纳的承诺或保证。
                        </li>
                    </ol>
                    <h3>II. 协议规定的约束和限制</h3>
                    <ol>
                        <li>未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目或实现盈利的网站）。购买商业授权请登陆http://haowei.tech 参考相关说明。
                        </li>
                        <li>不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。</li>
                        <li>无论如何，即无论用途如何、是否经过修改或美化、修改程度如何，只要使用 Webod 的整体或任何部分，未经书面许可，个人博客页面页脚处的 Webod 名称和好为科技有限公司下属网站（
                            http://webod.cn 或 http://haowei.tech） 的链接都必须保留，而不能清除或修改。
                        </li>
                        <li>禁止在 Webod 的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。</li>
                        <li>如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。</li>
                    </ol>
                    <h3>III. 有限担保和免责声明</h3>
                    <ol>
                        <li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
                        <li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。</li>
                        <li>好为科技有限公司不对使用本软件构建的个人博客中的文章或信息承担责任。</li>
                    </ol>
                    <p>有关 Webod 最终用户授权协议、商业授权与技术服务的详细内容，均由 Webod
                        官方网站独家提供。好为科技有限公司拥有在不事先通知的情况下，修改授权协议和服务价目表的权力，修改后的协议或价目表对自改变之日起的新授权用户生效。</p>
                    <p>电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始安装
                        Webod，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反
                        本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</p>

                </div>
            </div>
        <?php elseif ($step === 2): ?>
            <fieldset class="pack">
                <legend>运行环境</legend>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <th width="160">项目</th>
                        <th>结果</th>
                    </tr>
                    <tr>
                        <td>OS</td>
                        <td><?= PHP_OS ?></td>
                    </tr>
                    <tr>
                        <td>PHP版本</td>
                        <td><?= PHP_VERSION ?></td>
                    </tr>
                    <tr>
                        <td>内存限制</td>
                        <td><?= ini_get('memory_limit') ?></td>
                    </tr>
                    <tr>
                        <td>附件上传</td>
                        <td><?= ini_get('post_max_size') ?></td>
                    </tr>
                    <?php foreach (['gd', 'json', 'curl', 'pdo', 'zip'] as $ext): ?>
                        <tr>
                            <td><?= strtoupper($ext) . '扩展' ?></td>
                            <td><?php if (extension_loaded($ext)): ?>ok<?php else: ?>error<?php getHash()->set('breakValidate', $step); endif; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </fieldset>
            <fieldset class="pack">
                <legend>目录读写权限</legend>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <th width="160">项目</th>
                        <th>结果</th>
                    </tr>
                    <?php foreach (['/data', '/data/log', '/data/config', '/data/backup', '/data/upload'] as $dir): ?>
                        <tr>
                            <td><?= $dir ?></td>
                            <td><?php if (is_writeable(__ROOT__ . $dir)): ?>ok<?php elseif (is_writeable(__DIR__ . '/data')): ?>warn<?php else: ?>error<?php getHash()->set('breakValidate', $step); endif; ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </fieldset>
        <?php elseif ($step === 3): ?>
            <fieldset class="pack">
                <legend>数据库配置</legend>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td width="160">数据库地址</td>
                        <td><input name="server_address" placeholder="localhost"/></td>
                    </tr>
                    <tr>
                        <td>数据库端口</td>
                        <td><input name="server_port" placeholder="3306" value="3306"/></td>
                    </tr>
                    <tr>
                        <td>数据库名称</td>
                        <td><input name="database_name" placeholder="请输入数据库名称"/></td>
                    </tr>
                    <tr>
                        <td>数据库用户</td>
                        <td><input name="database_user" placeholder="请输入数据库用户"/></td>
                    </tr>
                    <tr>
                        <td>数据库用户密码</td>
                        <td><input name="database_user_password" placeholder="请输入数据库用户密码"/></td>
                    </tr>
                    <tr>
                        <td>表前缀名</td>
                        <td><input name="database_table_prefix" placeholder="请输入表前缀名" value="wd_"/></td>
                    </tr>
                    <tr>
                        <td>启用长连接</td>
                        <td>
                            <div class="switch-button">
                                <div class="switch-button-bar"></div>
                            </div>
                            <span class="text mini-text">开启长连接模式，访问速度将会更快（仅特定模式支持PHP-FPM = static）</span></td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="pack">
                <legend>网站配置</legend>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td width="160">网站标题</td>
                        <td><input name="site_title" placeholder="请输入网站标题"/></td>
                    </tr>
                    <tr>
                        <td>Cookie密钥</td>
                        <td><input name="cookie_key" value="<?= getHash()->random() ?>"/></td>
                    </tr>
                    <tr>
                        <td>管理员账号</td>
                        <td><input name="user" placeholder="请输入管理员账号"/></td>
                    </tr>
                    <tr>
                        <td>管理员密码</td>
                        <td><input type="password" name="password" placeholder="请输入密码"/></td>
                    </tr>
                    <tr>
                        <td>确认密码</td>
                        <td><input type="password" name="repassword" placeholder="请确认密码"/></td>
                    </tr>
                    <tr>
                        <td>管理员邮箱</td>
                        <td><input name="email" placeholder="请输入管理员邮箱"/></td>
                    </tr>
                </table>
            </fieldset>
        <?php endif; ?>
    </div>
    <div class="footer">
        <?php if ($step === 1): ?>
            <?= Ui::Button('同意协议并安装', '?step=' . ($step + 1)) ?>
        <?php elseif ($step < 4): ?>
            <?= Ui::Button(getHash()->get('breakValidate') ? '请先修复错误，然后继续安装' : '下一步', $step === 3 ? 'javascript:void(0)' : '?step=' . ($step + 1), ['data-name' => $step === 3 ? 'checking' : null], getHash()->get('breakValidate')) ?>
        <?php else: ?>
            <?= Ui::Button('完成，去首页', 'index.php') ?>
        <?php endif; ?>
        <div class="copyright">&copy; 2011-2019 Webod All rights reserved</div>
    </div>
    <!--[if lte IE 9]>
    <style type="text/css">
        div.modal-mask {
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#80000000, endColorstr=#80000000);
        }

        div.modal-container {
            margin-top: 10% \0;
        }

        div.process-bar-tracker {
            filter: progid:DXImageTransform.Microsoft.gradient(GradientType=1, startColorstr=#0099ee, endColorstr=#00eeff);
        }
    </style>
    <![endif]-->
</div>
<script type="text/javascript" src="<?= Url::getInstance()->assets('js/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?= Url::getInstance()->assets('js/component.js') ?>"></script>
<script type="text/javascript">
    $('a[data-name=checking]').bind('click', function () {
        $(document.documentElement).append(
            '<div class="modal-mask"><div class="modal-container"><div class="modal-container-header"><span class="modal-container-header-title">安装进度<span class="process-bar-num"></span></span></div><div class="modal-container-content"><div class="process-bar"><div class="process-bar-tracker"></div></div></div></div></div>'
        );
        $.ajax({
            type: 'POST',
            url: 'install.php?action=prepareInstall',
            data: {
                db_host: $('input[name=server_address]').val(),
                db_user: $('input[name=database_user]').val(),
                db_name: $('input[name=database_name]').val(),
                db_password: $('input[name=database_user_password]').val(),
                db_port: $('input[name=server_port]').val(),
                db_table_prefix: $('input[name=database_table_prefix]').val()
            },
            success: function (res) {
                var i = 0;
                var processAnimation = setInterval(function () {
                    if (i <= 100) {
                        $('div.modal-container span.process-bar-num').text(i + '%');
                        $('div.process-bar-tracker').css('width', (i++) + '%');
                    } else {
                        clearInterval(processAnimation);
                    }
                }, 100);
            }
        })
    });
</script>
</body>
</html>
