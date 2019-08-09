<?php

defined('__ROOT__') or define('__ROOT__', __DIR__);
if (PHP_SAPI <> "cli") exit('Access Denied');

$raw = '<?php

/**
 *
 * @class BuildConfig
 */
final class BuildConfig
{
    const VERSION = {version};
    const VERSION_CODE = {code};
    const VERSION_NUMBER = {number};
    const VERSION_LOCALE = {locale};
    const VERSION_LANGUAGE = {lang};
    
    const BUILD_DATE = {date};
    const BUILD_DATETIME = {datetime};
}
';

file_put_contents(__ROOT__ . '/vendor/BuildConfig.php',
    strtr($raw, [
        '{version}' => '\'preview\'',
        '{code}' => '\'2.0.0\'',
        '{number}' => 20000,
        '{lang}' => '\'chs\'',
        '{locale}' => '\'zh-CN\'',
        '{date}' => date('Ymd'),
        '{datetime}' => date('YmdHis')
    ])
);