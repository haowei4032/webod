<?php

/**
 *
 * @class BuildConfig
 * @method static getBuildInfo()
 */
final class BuildConfig
{
    const VERSION = 'preview';
    const VERSION_CODE = '2.0.0';
    const VERSION_NUMBER = 20000;
    const VERSION_LOCALE = 'zh-CN';
    const VERSION_LANGUAGE = 'chs';

    const BUILD_DATE = 20190809;
    const BUILD_DATETIME = 20190809214403;

    /**
     * @param string $name
     * @param array $arguments
     * @return string|null
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name === 'getBuildInfo') return self::VERSION_CODE . '_' . self::VERSION . '_' . self::VERSION_LANGUAGE . '_build_' . self::BUILD_DATE;
        return null;
    }
}
