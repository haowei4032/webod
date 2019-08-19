<?php

$startMs = round(microtime(1) * 1000);
ob_start();
defined('DEBUG') or define('DEBUG', 0);
defined('LOG_LEVEL') or define('LOG_LEVEL', 0);

if (version_compare(PHP_VERSION, '5.4.0', '<')) exit('The PHP version is too low');
if (is_file(__ROOT__ . '/data/config.php')) require_once __ROOT__ . '/data/config.php';

spl_autoload_register(function ($class) {

    $group = explode('\\', $class);
    if (count($group) > 1 && in_array(current($group), ['model', 'models'])) {
        $file = __ROOT__ . '/' . implode(DIRECTORY_SEPARATOR, $group) . '.php';
        if (!is_file($file)) throw new ErrorException('No such file "' . $file . '"');
        require_once $file;
        return;
    }

    $file = __ROOT__ . '/vendor/' . ($class === 'BuildConfig' ? 'BuildConfig' : 'class/' . $class) . '.php';
    if (!is_file($file)) throw new ErrorException('No such file "' . $file . '"');
    require_once $file;

}, true);

register_shutdown_function(function () {
    global $startMs;
    getHash()->save();
    getPdo($release = true);
    echo ob_get_clean();
    var_dump(round(microtime(1) * 1000) - $startMs);
    saveLogger();
});

/**
 * 获取构建信息
 * @return array|null
 * @throws Exception
 */
function getBuildConfig()
{
    if (!class_exists('BuildConfig')) return null;
    $reflection = new ReflectionClass('BuildConfig');
    return $reflection->getConstants();
}

/**
 * 获取常量
 * @param $name
 * @return mixed|null
 */
function getConstant($name)
{
    if (!defined($name)) return null;
    return constant($name);
}

/**
 * 获取数据库连接
 * @param null $release
 * @return PDO|null
 */
function getPdo($release = null)
{
    static $pdo = null;
    if ($release) {
        if ($pdo) getLogger()->info('释放PDO');
        return ($pdo = null);
    }
    if ($pdo) return $pdo;
    try {
        $dsn = 'mysql:dbname=' . getConstant('DB_DATABASE') . ';host=' . getConstant('DB_HOST') . ';port=' . getConstant('DB_PORT');
        $pdo = new PDO($dsn, getConstant('DB_USER'), getConstant('DB_PASSWORD'), [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names ' . getConstant('DB_CHARACTER'),
            PDO::ATTR_PERSISTENT => getConstant('DB_PERSISTENT_CONNECT'),
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (Exception $ex) {
        getLogger()->error('PDO错误', $ex->getMessage());
    }
    return $pdo;
}

/**
 * 获取请求数据
 * @return Request
 */
function getRequest()
{
    return Request::getInstance(null);
}

/**
 * 获取日志组件
 * @return Logger
 */
function getLogger()
{
    return Logger::getInstance(null);
}

/**
 * 获取哈希组件
 * @return Hash|HashAgent
 */
function getHash()
{
    return HashAgent::getInstance(null);
}

/**
 * 获取菜单
 * @param int $type
 * @return array|null
 */
function getMenu($type = null)
{
    return ModelAgent::getInstance()->setTableName('$menu')->where(function (Model $query) use ($type) {
        if (!is_null($type)) $query->where('type', intval($type));
    })->get();
}

/**
 * 获取模型
 * @param string $tableName
 * @return mixed
 */
function getModel($tableName)
{
    return ModelAgent::getInstance()->setTableName($tableName);
}

/**
 * 写入日志
 * @return mixed
 */
function saveLogger()
{
    if (!is_dir(__ROOT__ . '/data/log')) mkdir(__ROOT__ . '/data/log', 0755, true);
    $file = __ROOT__ . '/data/log/' . date('Ymd') . '.log';
    $fp = fopen($file, 'a+');
    foreach (getLogger()->getStack() as $body) {
        $body = array_values($body);
        fputs($fp, $body[0] . PHP_EOL);
    }
    fclose($fp);
}

/**
 * 添加普通响应器
 * @param string $name
 * @param string $mime
 * @param callable $callable
 * @param int $expires
 * @return mixed
 */
function addResponse($name, $mime, $callable, $expires = 3600)
{
    if (preg_match('#^(.+)=(.+)$#', $name, $submatch)) {
        list($_, $name, $value) = $submatch;
        $group = explode('|', $value);
        if (isset($_GET[$name]) && in_array($_GET[$name], $group)) {
            ob_clean();
            header('Content-Type: ' . (empty($mime) ? 'text/plain' : $mime));
            if (in_array($mime, ['image/png', 'image/jpeg'])) {
                header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
                header('Pragma: cache');
                header('Cache-Control: max-age=' . $expires);
            }
            ob_start();
            $returnValue = call_user_func_array($callable, [$_REQUEST]);
            header('Content-Length: ' . strlen($returnValue));
            echo $returnValue;
            exit;
        }
    }
}

/**
 * 添加Json响应器
 * @param string $name
 * @param callable $callable
 * @return mixed
 */
function addJsonResponse($name, $callable)
{
    addResponse($name, 'application/json', function () use ($callable) {
        $array = call_user_func_array($callable, [$_REQUEST]);
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    });
}

/**
 * 检查提供者
 * @return mixed
 */
function checkProvider()
{
    if (!is_file(__ROOT__ . '/data/install.lock')) {
        getLogger()->info('安装检测', '首次安装');
        Url::getInstance()->redirect('install.php');
    }
}

/**
 * 获取模板
 * @param string $subdir
 * @param string $name
 * @return mixed
 */
function template($subdir, $name)
{
    $file = __ROOT__ . '/template/' . $subdir . '/' . $name . '.php';
    if (is_file($file)) {
        require_once $file;
    } else {
        require_once __ROOT__ . '/template/base/error.php';
    }
}

/**
 * 获取帖子列表
 * @param int $id
 * @param array $filter [optional]
 * @return array|null
 */
function getPosts($id, $filter = [])
{
    return [
        getPostsDetail($id, $filter)
    ];
}

/**
 * 获取帖子列表
 * @param int $id
 * @param array $filter [optional]
 * @return array|null
 */
function getPostsDetail($id, $filter = [])
{
    return [
        'posts_id' => 1,
        'posts_title' => '打开一个测试页面',
        'posts_body' => 'asdfasdfasdfasdfasdfasdfasdfasdf',
        'posts_publish_time' => strtotime('2019-07-22 13:52:20'),
        'create_time' => strtotime('2019-07-21 :10:25:10'),
    ];
}
