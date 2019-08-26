Hymie PHPMVC 框架实现了较低侵入的 mvc 模式。
## 一、 控制器
控制器为普通 php 类，这样便于进行单元测试。

### 1.1 控制器类

> 对于 Controller 的唯一要求是，有无参构造函数或者构造函数所有参数有默认值。

1. 在 hymie MVC 框架中控制器为普通的 php 类, 控制器与路由的对应关系在模块的 `config.router.php` 中配置.控制器的开头需要声明 `namespace ......` .比如: 控制器文件在 `[web_root]/app/web/controller/IndexController.php` 那么控制器类应该具备以下形式

```
<?PHP
namespace web\controller;

class IndexController 
{
  public function index()
  {
    $model = ['x'=>1, 'y'=>2];
    // 如果使用 phpview, 那么视图文件名默认是不需要 .php 后缀, 其他模板引擎请参考模板引擎配置.
    retun result()->success($model)->message("success")->setView("index");
  }
}
```

2. 如果上述控制器的 `index` 方法映射 `/` 即站点跟目录, 那么在 `config.router.php` 中需要做以下配置:

```
return array(
  '/' => 'web\\controller\\IndexController'
  // or
  '/' => 'web\\controller\\IndexControler:index'
  // or
  '/' => ['web\\controller\\IndexController']
  //or
  '/' => ['web\\controller\\IndexController', 'index']
)
```
> 控制器默认会由 BeanFactory 加载, 默认情况下控制器在当前 http 请求中是 **单例** 的，如果使用 APCu 缓存则控制器跨请求也是单例的。

### 1.2 控制器方法参数

控制器方法可以定义参数, 框架会通过路由规则（下一节详细说明）、预定义变量获取:

1. PathVariable    
根据路由规则获取 URL 路径参数, 如路径参数数量不满足函数参数数量则会使用 2,3 点继续赋值。

  PathVariable 本质上是使用正则表达式分组功能实现，请参考以下PathVariable 示例：

  ```
  // 分页， 支持:
  // http://example.com/index.php?g=/a/1/10 （第1页，每页10条）
  // http://example.com/index.php?g=/a/1 （第1页，每页条数使用默认值10条）。
  '/product/list/(\d+)(?:(?:/)(\d+))?' => ['mod1\controller\ProductController', 'list']
  ```
  在上方的正则表达式中：

  * `(\d+)`:  匹配 1-n 个数字
  * `(?:(?:/)(\d+))?`: `?:` 代表匹配但不捕获分组，因此最外层 `(?:....)?` 代表匹配 0-1 个内层，如有则继续匹配子表达式，但不捕获分组。内层 `(?:/)` 匹配路径中的 `/` 但不捕获，`(\d+)` 匹配 1-n 个数字，也就是每页的条数。

  以上正则表达式对应的控制器定义为：

  ```
  <?PHP
  namespace mod1\controller;

  class ProductController 
  {
    public function list($page, $pageSize = 10) 
    {
      // controller code
    }
  }
  ```

2. 全局数组  
`$_GET` `$_POST` `$_REQUEST` 中寻找与参数名相同的变量进行赋值

3. 若控制器还有未赋值参数, 如果这些参数有默认值则会使用默认值, 否则赋值 `null`

> 注:参数值会默认进行 xss 过滤

### 1.3 路由配置文件
在每个模块目录下需要定义 `config.router.php` 文件来定义路由与控制器关系.如果模块目录不存在这个配置文件, 那么模块下的路由配置将不会被加载。

> 注: 虽然不一定要在每个模块下定义路由配置, 比如可以在一个模块下定义所有路由.不过将模块和模块路由定义在一个目录可以提高代码可维护性, 另外也可以减少路由表大小, 节约执行开销.

> 注: **配置 URL 映射表达式必须以 `/` 开头，比如: `/login` `/` 等

路由配置文件支持 [PHP PCRE 正则表达式](https://www.php.net/manual/zh/book.pcre.php) 正则表达式。路由配置的详细信息请参考 **配置** 部分。

```
// config.router.php
// 路由必须以 / 开头
return array(
  /* 匹配 /product/12/zbc */
  '/product/(\d+)/([a-zA-Z]{1,3})' => ['\\web\\controller\\ProductController'],

  /**
   * 分页 URL, 匹配: 
   *  /product/p/10 第10页
   *  /product/p/10/20 第十页每页20行
   */
  '/product/p/(\d+)(?:(?:/)(\d+))?' => ['\\web\\controller\\ProductController', 'list']
);

//ProductController.php

<?PHP
namespace web\controller;
class ProductController
{
  public function index($id, $shortName)
  {

  }

  public function list($page, $pageSize = 10)
  {

  }
}

```
### 2.2 视图

视图模板默认需要保存在 `[web_root]/app/[module]/view` 目录下.每个模块保存自己的视图文件

#### 2.2.1 视图位置
1. 视图目录位置  
  默认视图位置在 `[web_root]/app/[module]/view` 目录下，每个模块必须有 `view` 目录。

  如果需要修改视图默认位置，可在 `config.php` 文件中修改 `$config['view']['php_view_root'] = APP_ROOT;` 配置为新目录，但新目录有如下要求:

  * 新目录下需有与模块名相同的子目录（如果模块包含路由定义的话）
  * 每个模块子目录下需要有 `view` 目录用于存放视图文件

2. 视图文件位置  

  建议用目录组织每个模块视图目录下的视图文件，控制器返回视图时用 `/` 分割目录，比如默认视图为 `php`，那么 `web` 模块控制器返回视图：

  * `return result()->setView('index/index')->success();`  
    对应 `[web_root]/app/web/view/index/index.php`

  * `return result()->setView('login/login_in')->success();`  
  对应 `[web_root]/app/web/view/login/login_in.php` 

#### 2.2.2 视图种类
hymie MVC 框架目前支持 3 种视图:
- php 视图  
  默认视图, 使用 php 语法嵌入 html 页面.
- twig 视图  
  参考 [TWIG 官网](https://twig.symfony.com/)
- json 视图  
  根据 http 请求, 如果是 ajax 请求则默认使用 json 视图.

#### 2.2.3 选择视图  
  使用 `result()` 函数获取 `\hymie\Result` 对象返回模型及视图. 可以通过 '[type]:[filename]'的方式选择视图类型, 比如: `php:login/login` `twig:login/login` `json:login/login`

  ```
  /**
   * 选择视图 [web-root]/app/[module]/view/login/login.php
   */
  public function someMethod()
  {
    result()->success()->setView('login/login');
  }

  /**
    * 使用 twig 处理视图, 视图文件为 [web-root]/app/[module]/view/login/login.[suffix]
    *  [suffix] 是在 twig 中配置的后缀名.
    */
  public function someMethod()
  {
    result()->success()->setView('twig:login/login');
  }

  /**
   * 如果控制器方法返回 null, 则代表控制器直接输出内容, 框架不会再做其他处理
   */
  public function someMethod()
  {
    echo "......";
    return null;
  }
  ```

#### 2.2.4 扩展视图
如系统提供的视图不满足要求，可实现自己的视图处理类。需要完成以下工作。

1. 自定义视图类需继承 `\hymie\view\View` 抽象类，实现 `abstract public function render($result)` 方法，并且其构造函数需要以下形式 `public function __construct($module, $file)`

```
namespace my\view;

class MyViewImpl extends \hymie\view\View 
{
  /**
   * @param $module string 模块名
   * @param $file string 视图文件名，根据视图实现，可带后缀或不带后缀
  public function __construct($module, $file) 
  {

  }

  public function render($result)
  {

  }
}
```

> 实现自定义视图时可以参考框架内视图实现代码。

2. 在配置文件 `config.php` 中注册视图，如：
```
  $config['view']['implements'] = [
      'myviewimpl' => 'my\view\MyViewImpl'
  ];
```
3. 可以在 `config.php` 中指定自定义扩展视图为默认视图 
```
  $config['view']['default'] = 'myviewimpl';
```
4. 可以在运行时选择视图
```
  public function someMethod()
  {
      return result()->setView('myviewimpl:view_name')->success();
  }
```


### 2.3 Result 对象

`\hymie\Result` 对象是返回结果的帮助对象.

1. 当使用 json 时会返回如下格式的 json 信息  
```
    {
      code: 0|1|-1,
      data: [],
      message: ''
    }
```

2. 如果 php 或者 twig (以及其他) 模板引擎时会将 `Result` 中的 `model` 数组传递到模板引擎.

3. Result 对象说明

- add($key, $value): 添加数据到指定 key.
- addArray($array): 添加键值对的模型数据, 会与当前已有的数据合并.
- success($array = null): 设置结果为成功, 并添加键值对的模型数据, 会与当前已有的数据合并.
- fail($array = null): 设置结果为失败, 并添加键值对的模型数据, 会与当前已有的数据合并.
- error($array = null): 设置结果为错误, 并添加键值对的模型数据, 会与当前已有的数据合并.
- setView($viewName): 设置视图

以上方法都支持链式调用

```
  return result()->add('a', 1)->addArray(['b'=>2])->success(['c'=>3])->setView();
```

5. `result()` 帮助函数  
定义在 `function.php` 中, 仅简单构造 `\hymie\Result` 实例并返回.

