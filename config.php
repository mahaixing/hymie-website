<?PHP
/**
 * 定义环境配置，可接受的环境配置有：
 *
 *      true         开发环境
 *      false        正式环境
 */
define('DEBUG', true);

/**
 * 是否允许日志记录
 */
define('LOG_ENABLE', true);

/**
 * 网站地址，必须以 http(s):// 开头，末尾不需 /
 */
define('HTTP_HOST', 'http://hymie.iautoo.cn');

/**
 * 定义常量，所有页面都需要检查这个常量，如果没有则代表直接访问该php
 * 需要在所有php文件中增加以下内容：
 *
 * defined('ROOT') OR exit('No direct script access allowed');
 *
 */
define('ROOT', __DIR__);

/**
 * 应用主目录
 */
define("APP_ROOT", ROOT . DIRECTORY_SEPARATOR . "app");

/**
 * 默认字符集
 */
define("CHARSET", 'UTF-8');

/**
 * 默认时区
 */
define("TIMEZONE", "Asia/Shanghai");

/**
 * 需要引入 composer 的 autoload
 */
include ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = array();

/**
 * 控制器配置
 */
// 方法参数是否进行 xss 过滤
$config['controller']['xss'] = true;

/**
 * monolog 配置
 *      'DEBUG'
 *      'INFO'
 *      'NOTICE'
 *      'WARNING'
 *      'ERROR'
 *      'CRITICAL'
 *      'ALERT'
 *      'EMERGENCY'
 */
// *** 建议修改这个路径，到web主目录外。***
$config['logger']['name'] = 'HYMIE';
$config['logger']['path'] = ROOT . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'application.log';
$config['logger']['level'] = 'DEBUG';
$config['logger']['max_files'] = 30;
//$config['logger']['format'] = ["[%datetime%] %channel%.%level_name% : %message% - %context% \n","Y-m-d H:i:s"];

/**
 * enable_query_string:
 *  true 使用查询字符串模式
 *  false 使用 path_info 模式
 *
 * query_string_key:
 *  当使用查询字符串模式时，用来指定参数名，仅当 enable_query_string 时可用
 *
 * rewrite_enabled:
 *  web 服务器 url 重写是否已启用，这会影响 U 函数生成的链接
 */
$config['url']['enable_query_string'] = false;
//当 enable_query_string = true 时，用来代表路径的 query_string 参数名
$config['url']['query_string_key'] = 'g';
// 如果 web 服务器配置了 url write 需要将此配置设置为 true
// 仅在 enable_query_string = false 时可用
$config['url']['rewrite_enabled'] = false;

/**
 * 分页配置
 * 目前只支持 PdoPage
 */
$config['pagination']['page_key'] = 'p';
$config['pagination']['size_key'] = 's';

/**
 * 视图类型，支持:
 *  1. php
 *  2. twig
 *  3. json
 *
 * php 类型视图需要配置 $config['view']['php']['root']  = [path-to-php-file-root]
 *
 * twig 类型视图配置在 provider 部分进行配置。
 *
 * json 类型视图是根据 content-type 进行判断，如果是异步请求则不参考视图配置，默认返回 JsonView
 *
 * 视图可以在运行时选择，比如返回的视图为："twig:admin/foo/bar.html" 则表示使用twig引擎处理视图。
 *  同样返回 "json:" 代表使用json视图
 */
$config['view']['default'] = 'twig';

/**
 * redis session 配置
 *
 * 使用 '\hymie\session\RedisSession' 为注册的 session handler ;
 *
 * 使用默认 session，文件形式 session
 * $config['session']['redis']                = FALSE;
 *
 * 使用 redis 存储 session
 * $config['session']['redis']                = TRUE;
 *
 * 如果使用 pecl 的 phpredis 实现，则 $config['session']['redis'] 需要设置为 false
 */
$config['session']['redis'] = false;
$config['session']['expiration'] = 7200;
// 尽在 redis 中使用，存储 session 的 redis 库名
$config['session']['db'] = null;
//如果前端有负载均衡，则此配置无意义
$config['session']['match_ip'] = false;

/**
 * cookie 配置
 */
$config['cookie']['prefix'] = '';
$config['cookie']['domain'] = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$config['cookie']['path'] = '/';
$config['cookie']['secure'] = false;
$config['cookie']['httponly'] = false;

/**
 * upload 配置
 * type = file | qiniu
 */
$config['upload']['type'] = 'file';

// file 类型上传配置
//不要以 / 或者 \ 结尾。
$config['upload']['file']['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']) . '/upload';
//file upload config
// 如果为数字则单位为 Bbyte，如为字符串则支持 'K' 'M' 'G' 'K' 单位
$config['upload']['file']['max_file_size'] = '2M';
$config['upload']['file']['max_file_uploads'] = 10;
// '*' 代表允许所有文件, 如果不设置则默认为允许所有文件
$config['upload']['file']['allowed_types'] = 'jpeg|jpg|png|zip|doc';

$GLOBALS['_config'] = &$config;
