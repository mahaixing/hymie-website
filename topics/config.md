Hymie phpmvc 框架有多个配置文件，配置文件分类 _模块配置文件_ 和  _框架配置文件_ 两类。

| 文件名             | 用途          | 是否必须          |
| ----------------- | ------------- | --------------- |
| config.php        | 框架配置文件    | 是               |
| config.bean.php   | bean 配置文件  | 是               |
| config.router.php | 路由配置文件    | 至少一个模块需要有 |
| config.filter.php | 过滤器配置文件   | 否              |
 
## 一、生成配置文件

### 1.1 使用 `hymie` 脚本
可以使用 `hymie` 脚本生成配置文件，假设通过 composer 安装框架

```
    // 拷贝 config.php config.bean.php 到 web_root
    vendor/bin/hymie copyconfig .
```

创建模块是会默认在模块目录下生成 `config.router.php` `config.filter.php`，如模块不需要可删除。

```
    vendor/bin/hymie add [module_name] .
```
## 二、框架配置文件

### 1.1 `config.php`

`config.php` 是框架主配置文件，包括：

1. 常量定义、
2. 包含 `composer` 的 `autoload.php` 
3. 日志、路由、视图、session、cookie、文件上传 配置

以下为 `config.php` 的模板文件

```
<?php
/*
 * 网站地址，必须以 http(s):// 开头，末尾不需 /
 */
// define('HTTP_HOST', '');

/*
 * 定义常量，所有页面都需要检查这个常量，如果没有则代表直接访问该php
 * 需要在所有php文件中增加以下内容：
 *
 * defined('ROOT') OR exit('No direct script access allowed');
 *
 */
define('ROOT', __DIR__);

/*
 * 应用主目录
 */
define('APP_ROOT', ROOT.DIRECTORY_SEPARATOR.'app');

/*
 * 默认字符集
 */
define('CHARSET', 'UTF-8');

/*
 * 默认时区
 */
define('TIMEZONE', 'Asia/Shanghai');

/**
 * 需要引入 composer 的 autoload.
 */
include ROOT.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$config = array();

/*
 * 定义环境配置，可接受的环境配置有：.
 *
 *      true         开发环境
 *      false        正式环境
 */
$config['debug'] = true;

/*
 * 是否允许日志记录
 */
$config['log_enable'] = true;

/*
 * 控制器配置
 */
// 方法参数是否进行 xss 过滤
$config['controller']['xss'] = true;

/*
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
$config['logger']['path'] = ROOT.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'application.log';
$config['logger']['level'] = 'DEBUG';
$config['logger']['max_files'] = 30;
//$config['logger']['format'] = ["[%datetime%] %channel%.%level_name% : %message% - %context% \n","Y-m-d H:i:s"];

/*
 * 路由、过滤器缓存是可清理缓存，系统更新或者修改后可能会新增路由配置、控制器、过滤器，因此需要上线后清理已缓存配置。
 *
 * 默认会使用 \hymie\cache\impl\ApcuCache，但如果 apcu 未启用则会使用下方配置的默认替换缓存。
 *
 * 路由和过滤器的替换缓存，默认与系统缓存一致，但这两个缓存是可清理的。
 *
 * PSR-6 PSR-16 规范无法遍历缓存 key，所以清理实际上是清理整个缓存，也就是业务数据的缓存也会被清理，
 * 因此，如不希望业务数据缓存被清理，则需要为他们配置单独的缓存，比如：
 *
 * 系统缓存: redis
 * Filter、Cache 缓存： \Symfony\Component\Cache\Adapter\FilesystemAdapter （Apcu 未启用的情况下）
 */
$config['cache']['filter'] = \hymie\cache\Cache::DEFAULT_BAEN_NAME;
$config['cache']['router'] = \hymie\cache\Cache::DEFAULT_BAEN_NAME;

/*
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
$config['url']['enable_query_string'] = true;
//当 enable_query_string = true 时，用来代表路径的 query_string 参数名
$config['url']['query_string_key'] = 'g';
// 如果 web 服务器配置了 url write 需要将此配置设置为 true
// 仅在 enable_query_string = false 时可用
$config['url']['rewrite_enabled'] = false;

/*
 * 分页配置
 * 目前只支持 PdoPage
 */
$config['pagination']['page_key'] = 'p';
$config['pagination']['size_key'] = 's';

/*
 * 视图类型，支持:
 *  1. php
 *  2. twig
 *  3. json
 *
 * php 视图只能以 .php 作为文件后缀
 *
 * twig 类型视图配置在 config.bean.php 中配置
 * twig 视图如果没有提供后缀名，则默认 .html 作为后缀.
 *
 * json 类型视图是根据 content-type 进行判断，如果是异步请求则不参考视图配置，默认返回 JsonView
 *
 * 视图可以在运行时选择，比如返回的视图为："twig:admin/foo/bar.html" 则表示使用twig引擎处理视图。
 *  同样返回 "json:" 代表使用json视图
 *
 * 视图有唯一的要求，必须是 [view_root]/[module]/view 的目录结构
 *
 *  所有视图文件的默认位置是 [web_root]/app/
 *  php 视图文件位置可以在下方的 $config['view']['php_view_root'] 配置项中配置
 *  twig 视图文件位置可以修改 config.bean.php 中配置 twig bean 的构造函数参数。
 *
 */
$config['view']['default'] = 'php';
// $config['view']['php_view_root'] = APP_ROOT;

/*
 * 注册应用实现的视图类，可以有多个，需要继承 \hymie\view\View
 * $config['view']['implements] = [
 *  ['view_name', 'namespace\class_name']
 * ]
 */
$config['view']['implements'] = [];

/*
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

/*
 * cookie 配置
 */
$config['cookie']['prefix'] = '';
$config['cookie']['domain'] = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$config['cookie']['path'] = '/';
$config['cookie']['secure'] = false;
$config['cookie']['httponly'] = false;

/*
 * upload 配置
 * type = file | qiniu
 */
$config['upload']['type'] = 'file';

// file 类型上传配置
//不要以 / 或者 \ 结尾。
$config['upload']['file']['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/upload';
//file upload config
// 如果为数字则单位为 Bbyte，如为字符串则支持 'K' 'M' 'G' 'K' 单位
$config['upload']['file']['max_file_size'] = '2M';
$config['upload']['file']['max_file_uploads'] = 10;
// '*' 代表允许所有文件, 如果不设置则默认为允许所有文件
$config['upload']['file']['allowed_types'] = 'jpeg|jpg|png|zip|doc';

$GLOBALS['_config'] = &$config;
```

### 1.2 bean 配置文件
bean 工厂请参考 [Bean工厂](#) 部分，以下为模板配置

```
<?PHP
/**
 * bean工厂配置文件，根据 bean 配置数组来构造对象，需要遵循 PSR-4 标准的
 * 自动加载机制。
 *
 * bean 工厂创建的 bean 是单例的，如不能使用单例 bean，需自行创建对象。
 * bean 工厂创建的 类对象 不是单例的，每次创建的类均为新的实例。
 *
 * 支持：
 *  1. 构造函数，构造函数参数。
 *  2. 工厂类，工厂类参数
 *  3. 属性赋值
 *  4. 类循环引用（有限制）
 *
 * 以数组形式定义 bean:
 *  <code>$beans = array();</code>
 *
 * 1. bean 定义，使用 'class' 定义 bean，定义需要包含 namespace
 *      $beans['url']['class'] = '\hymie\Url';
 *
 * 2. 定义构造函数
 *  $beans['mockbean'] = [
 *      'class' => 'beans\MockBean',
 *      'construct-args' => [
 *          'propa' => 1,
 *          'propb' => 2
 *      ]
 *  ];
 *
 * 3. 定义工厂方法，
 * 注意：如果工厂函数如果不是静态的，那么工厂类需要有无参构造函数。
 *      如果工厂函数是静态的，则对工厂类的构造函数无要求。
 *  $beans['mockbean2'] = [
 *      'factory-class' => 'beans\MockBean',
 *      'factory-method' => 'getInstance',
 *      'factory-method-args' => [
 *          'propa' => 4,
 *          'propb' => 5
 *      ]
 *  ];
 *
 * 4 定义属性（构造函数和工厂方法定义一致，这里使用构造函数做实例）
 *  $beans['mockbean3'] = [
 *      'factory-class' => 'beans\MockBean',
 *      'factory-method' => 'getInstance',
 *      'factory-method-args' => [
 *          'propa' => 4,
 *          'propb' => 5
 *      ],
 *      'props' => [
 *          'propa' => 6,
 *          'propb' => 7
 *      ]
 *  ];
 *
 * 5 定义 bean 依赖
 *  $beans['refa1'] = [
 *      'class' => 'beans\RefA',
 *      'construct-args' => [
 *          'refb' => 'ref:refb'
 *      ]
 *  ];
 *
 *  $beans['refa2'] = [
 *      'factory-class' => 'beans\RefA',
 *      'factory-method' => 'getInstance',
 *      'factory-method-args' => [
 *          'refb' => 'ref:refb'
 *      ]
 *  ];
 *
 *  $beans['refb'] = [
 *      'class' => 'beans\RefB'
 *  ]
 *
 * 6. 定义循环依赖，beanA 依赖 beanB，同时 beanB 也依赖 beanA，因此在定义 bean 时需要注意：
 *  6.1 不能都使用 构造函数 或者 工厂方法 或者 构造函数-工厂方法 的方式，会死循环。
 *  6.2 beanA 可以使用 构造函数 或 工厂方法 的方式定义 beanB 的依赖关系，beanB 使用属性方式定义 beansA 的依赖关系。
 *  6.3 可以同时使用属性的方式定义双方依赖关系。
 *
 *  $beans['cyca'] = [
 *      'class' => 'beans\CycleA',
 *      'construct-args' => [
 *          'cycleB' => 'ref:cycb'
 *      ]
 *  ];
 *
 *  $beans['cycb'] = [
 *      'class' => 'beans\CycleB',
 *      'props' => [
 *          'cycleA' => 'ref:cyca'
 *      ]
 *  ];
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @param {type}
 * @return:
 */

$beans = array();

// predis bean
// $beans['predis'] = [
//     'class' => 'Predis\Client',
//     'construct-args' => [
//         'parameters' => ['scheme' => 'tcp', 'host'   => '10.0.0.1', 'port'   => 6379],
//         'options' => ['prefix' => 'hymie:']
//     ]
// ];

// cache bean
// $beans['cache'] = [
//     // 'class' => '\Symfony\Component\Cache\Adapter\FilesystemAdapter',
//     'class' => '\Symfony\Component\Cache\Adapter\RedisAdapter',
//     'construct-args' => [
//         'ref:predis'
//     ]
// ];

// $beans['twig_loader'] = [
//     'class' => '\Twig\Loader\FilesystemLoader',
//     'construct-args' => [
//         APP_ROOT . DIRECTORY_SEPARATOR
//     ]
// ];

// $beans['twig'] = [
//     'class' => '\Twig\Environment',
//     'construct-args' => [
//         'ref:twig_loader',
//         ['cache' => '{path-to-cache-directory}']
//     ]
// ];

//medoo bean
// $beans['medoo'] = [
//     'class' => 'Medoo\\Medoo',
//     'construct-arg' => [
//         'database_type' => 'mysql',
//         'database_name' => 'name',
//         'server' => 'localhost',
//         'username' => 'your_username',
//         'password' => 'your_password',
//     ],
// ];

// pdo bean
// $beans['pdo'] = [
//     'class' => 'PDO',
//     'construct-args' => [
//         'dsn' => 'mysql:dbname=gszx;host=127.0.0.1',
//         'user' => 'root',
//         'password' => '123456',
//         [\PDO::ATTR_PERSISTENT => true]
//     ]
// ];

$GLOBALS['_beans'] = &$beans;
```

## 二、模块配置文件
每个模块根据需要可以有 `config.router.php` 或者 `config.filter.php`，当然也可以没有，这类模块一般是通用模块或者库模块。

### 2.1 `config.router.php` 

该配置文件用于配置路由, 路由配置格式如下：

1. path_regex => 'controllerClass:methodName'，匹配 `controllerClass` 控制器的 methodName 方法，比如：  
`'/' => 'module_dir_name\controller\IndexController:someMethod'`

2. path_regex => 'controllerClass'，匹配 `IndexController` 控制器默认方法 index，比如:  
`'/' => 'module_dir_name\controller\IndexController'`

3. path_regex => ['controllerClass', 'methodName']，匹配 `controllerClass` 控制器的 methodName 方法，比如：   
`'/' => ['module_dir_name\controller\IndexController', 'methodName']`

4. path_regex => ['controllerClass']，匹配 `controllerClass` 控制器的默认方法 index，比如：   
`'/' => ['module_dir_name\controller\IndexController']`

示例：

```
<?PHP
/**
 * 模块路由配置，使用正则配置，参考如下元字符
 * \d       任意十进制数字
 * \D       任意非十进制数字
 * \h       任意水平空白字符(since PHP 5.2.4)
 * \H       任意非水平空白字符(since PHP 5.2.4)
 * \s       任意空白字符
 * \S       任意非空白字符
 * \\v       任意垂直空白字符(since PHP 5.2.4)
 * \V       任意非垂直空白字符(since PHP 5.2.4)
 * \w       任意单词字符
 * \W       任意非单词字符
 *
 * []       选择   [a-zA-Z0-9]* 包含任意 大小写字母和数字，也可没有。
 * ()       分组，用于给controller方法赋值，根据定义顺序赋值。
 * *        0-多个
 * +        1-多个
 * {m,n}    m-n个
 *
 * 示例路由配置
 * 键名为定义的路由，值的格式为 控制器类名:方法名，控制器类名为包含命名空间的类名，命名空间
 * 相对于 modules 目录下模块子目录开始。
 *
 * 比如：
 *  app/web/controller/IndexController.php
 * 的命名空间为 \web\controller;
 *
 * '/' => '%s\controller\IndexController:index',
 *
 * @see https://www.php.net/manual/zh/book.pcre.php
 */
return array(
    // IndexController->someMethod
    '/' => '[module_dir_name]\controller\IndexController:someMethod',

    // 分页， 支持:
    // http://example.com/index.php?g=/a/1/10 （第1页，每页10条）
    // http://example.com/index.php?g=/a/1 （第1页，每页条数使用默认值10条）。
    '/product/list/(\d+)(?:(?:/)(\d+))?' => ['mod1\controller\ProductController', 'list'],

    // use default IndexController->index
    '/xyz' => '[module_dir_name]\controller\IndexController',

    // use default IndexController->index
    '/mnu' => ['[module_dir_name]\controller\IndexController']
);
```
### 2.2 `config.filter.php`

过滤器的配置格式为：

```
path_regex => [
    'class' => 'namespace\filterClass',
    'exclude' => 'path'
]

// 或者 

path_regex => [
    'class' => 'namespace\filterClass',
    'exclude' => ['path1', 'path2']
]
```
其中：
1. `path_regex` 需要过滤的路径正则表达式
2. `class` 过滤器类名
3. `exclude` 排除掉的路径正则表达式，可以是字符串或者数组

示例：

```
<?PHP
/**
 * 过滤器
 *
 * 过滤器根据定义先后执行。
 *
 * 过滤器配置。
 *      url: 拦截的 url 模式，正则表达式形式。
 *      [class|bean]: 具体类名，需包含命名空间；bean 名，配置在 config.bean.php 中的 bean
 *      exclude: 排除的 url，可以是字符串也可以是数组，支持正则
 *
 * @see https://www.php.net/manual/zh/book.pcre.php
 */

return array(

    // 以下为示例过滤器配置，做参考

    // '/admin/.*' => [
    //     'class' => '\\admin\\filter\\LoginFilter',
    //     'exclude' => '/login',
    // ]

);
```