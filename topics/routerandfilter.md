## 一、路由
Hymie PHPMVC 框架使用正则表达式定义路由。框架支持两种路由配置方式

* 配置文件 `config.router.php` 

* 注解 @RouterMapping(value="...")

基于框架开发的应用由一到多个模块组成，路由则定义在每个模块的 `config.router.php` 配置文件中。

> 路由定义采用正则表达式方式，请参考 **配置文件** 章节示例。

### 1.1 路由的处理方式
1. 环境区别  
  在 `config.php` 中 `$config]'debug']` 为 `true` 时，框架不缓存路由信息，为 `false` 时，框架会使用缓存 bean 缓存路由信息，记录路由请求次数，按照请求次数进行排序。也就是请求最多的路由会排在最前面，这样可以一定程度上提升路由解析的效率。

2. 加载流程
  框架启动后，会检查缓存中是否有路由配置，如有则加载，如没有则处理每个模块下的 `config.router.php` 文件，合并成路由表。
  
  > 框架在开发模式和正式模式下都会检查缓存路由表，只不过开发模式下使用的缓存是 `\hymie\cache\NullCache` 类，因此检查使用为 `false`

3. 路由处理流程
  框架会循环路由表中的路由，逐个匹配当前路径，若匹配则解析生成控制器实例、模块名、控制器方法反射对象、控制器方法参数数组等数据给 `Application` 对象，后者会调用控制器方法，并寻找对应模块下的视图文件渲染视图。

> 注：因此，每个模块只能定义自己模块下的路由。例外情况是，如果路由对应的控制器返回 `json` 视图，或者在控制器直接输出数据，或者跳转地址的情况可以不受此限制。

### 1.2 路由配置文件
在 **配置文件** 章节介绍了各种配置文件，这里针对路由配置再做一些介绍。

* 应用中至少一个模块需要有路由定义。

* 没有路由定义的模块可以理解为类库。

* 模块的路由定义文件名为 `config.router.php`，包含它时必须返回数组形式数据。

如果团队开发，担心多人编辑 `config.router.php` 会频繁产生版本冲突，可让团队成员分辨定义自己的路由文件，最后在 `config.router.php` 中整合这些路由文件，比如：

```
//config.router.a.php
return array(
    '/' => '[module_dir_name]\controller\IndexController:someMethod'
)

//config.router.b.php
return array (
    '/xyz' => '[module_dir_name]\controller\IndexController'
)

//config.router.php

$a = include __DIR__ . DIRECTORY_SEPARATOR . 'config.router.a.php';
$b = include __DIR__ . DIRECTORY_SEPARATOR . 'config.router.b.php';

$router = array(
    '/mnu' => ['[module_dir_name]\controller\IndexController']
)

$router = array_merge($router, $a);
$router = array_merge($router, $b);

return $router;
```

### 1.3 注解

框架支持通过注解的方式配置路由，在使用注解前需要先在控制器中引入注解类 `use hymie\annotation\RouterMapping;`

注解定义的方法参考以下代码示例

**以下示例中 SomeController 类只有默认方法 index 因此路由映射使用正则不会产生影响**

  ```
    use hymie\annotation\RouterMapping;
    
    /**
     * @RouterMapping(value="/(\w*)")
     */
    class SomeController
    {
        public function index($name)
        {
           // handle path  '/abc' '/def' /ghi
           // $name = "abc" or "def" or "ghi"
        }
    }
  ```

**如果类中其他方法也映射路由，那么这个类上的路由映射一般作为目录分割用，在类路由配置使用正则表达式的时候要细致考虑 URL结构**

  ```
  use hymie\annotation\RouterMapping;

  /**
    * @RouterMapping(value="/other")
    */
  class SomeOtherController
  {
      public function index()
      {
          // handle path  '/other'
      }

      /**
        * @RouterMapping(value="/foo-(\d{1,3})")
        */
      public function login($number)
      {
          // handle path '/other/foo-1" "/other/foo-123"
          // $number = 1 or $number = 123

          //could not handle '/ohter/foo-1234'
      }
  }
  ```
> 如果类的方法需要注解，那么类上的注解一般作为 URL 目录区分用，在这种情况下需要慎重使用正则表达式。

## 二、URL 
### 2.1 URL 模式
支持两种 URL 模式，PATHINFO 和 QueryString，在 `config.php` 中的 `url` 部分进行配置。`url` 配置会影响到 `\hymie\Url` 类生成链接的方式。

> 注: nginx 需要进行配置才能支持 PATHINFO，请参考 **服务器配置示例部分**

> 注: 如果要生成 url_rewrite 的 url, 需要在配置文件的 `url` 部分设置 `url_rewrite` = true

### 2.2 生成 URL 
页面中不能写死跳转到应用内的地址或者链接，因为修改 URL 模式后应用将无法正常工作，因此在需要跳转地址或者生成链接时需要使用 `\hymie\Url` 类，或者帮助函数 `_U` 以及 `U`。

#### 2.2.1 Url 类
Url 类是生成地址的工具类，它根据 `config.php` 中 `$config['url']` 的配置来动态生成 url 地址，比如以 **简介** 中的 `example` 项目为例。

1. 生成短 URL
```
    $url = new \hymie\Url('/a/b', ['a'=>1,'b'=>'2']);
    // or 
    $url = new \hymie\Url('/a/b', 'a=1&b=2');
    echo $url->getUrl() 
```
* 如果 `$config['url']['enable_query_string'] = true;`  
  则输出 `/example/index.php?g=a.b&a=1&b=2`
* 如果 `$config['url']['enable_query_string'] = false;`  
  则输出 `/example/index.php/a/b?a=1&b=2`
* 如果 `$config['url']['enable_query_string'] = false; $config['url']['rewrite_enabled'] = true;`  
  则输出 `/example/a/b?a=1&b=2`

2. 生成长 URL
  ```
    $url = new \hymie\Url('/a/b', ['a'=>1,'b'=>'2']);
    // or 
    $url = new \hymie\Url('/a/b', 'a=1&b=2');
    echo $url->getUrl(false);
  ```

  * 如果 `$config['url']['enable_query_string'] = true;`  
    则输出 `http://localhost/example/index.php?g=a.b&a=1&b=2`
  * 如果 `$config['url']['enable_query_string'] = false;`  
    则输出 `http://localhost/example/index.php/a/b?a=1&b=2`
  * 如果 `$config['url']['enable_query_string'] = false; $config['url']['rewrite_enabled'] = true;`  
        则输出 `http://localhost/example/a/b?a=1&b=2`

3. 跳转到其他入口文件（仅 `$config['url']['rewrite_enabled'] = false;` 时有效
    ```
        $url = new \hymie\Url('/x/y', ['a'=>1,'b'=>'2'], 'index_other.php');
        // or 
        $url = new \hymie\Url('/x/y', 'a=1&b=2', 'index_other.php');
        echo $url->getUrl() 
    ```
    * 如果 `$config['url']['enable_query_string'] = true;`  
    则输出 `/example/index_other.php?g=x.y&a=1&b=2`
    * 如果 `$config['url']['enable_query_string'] = false;`  
    则输出 `/example/index_other.php/x/y?a=1&b=2`
    * 如果 `$config['url']['enable_query_string'] = false; $config['url']['rewrite_enabled'] = true;`  
    则输出 `/example/x/y?a=1&b=2`

4. 启用 pathinfo 以及 url_rewrite 时
当启动 pathinfo 和 url_rewrite 时，对于不同入口文件需要在 web 服务器上做路径映射配置，具体请参考 **服务器配置示例**

#### 2.2.2 `U` `_U` `R` 函数
这两个函数是 `hymie\Url` 类的帮助函数

1. `U` 函数 `function U($path, $params = null, $script_name = '')`   
  直接输出短地址，如果 `$path` 是以 `http(s)` 开头的则是站外地址。

2. `_U` 函数 `function _U($path, $params = null, $script_name = '')`  
返回短地址，如果 `$path` 是以 `http(s)` 开头的则是站外地址。

3. `R` 函数 `function R($to)`   
跳转到 `$to` 地址，如果 `$to` 是以 `http(s)` 开头的则代表是站外地址。

### 2.3 上下文函数

`context_path()` 函数返回当前上下文，引用静态资源时可使用该函数。

### 2.4 静态资源

建议将静态资源统一放到 `static` 目录，或者其他名字的目录下，以便于统一管理，因为如果开启 pathinfo 和 url_rewrite 需要对 web 服务器做重写配置，静态资源统一放到单独目录下便于配置服务器重写规则。

## 三、过滤器
应用中可以定义多个过滤器，过滤器配置可参考 **配置文件** 章节。

过滤器按照定义顺序逐个执行，如果全部通过则执行控制器。

### 2.1 过滤器配置
如果模块目录下包含 `config.filter.php` 且应用程序使用了该模块, 则会自动加载此配置文件.配置文件中定义针对 `URL` 的过滤器配置, 配置使用 [PHP PCRE 正则表达式](https://www.php.net/manual/zh/book.pcre.php).

```
'/admin/.*' => [
     'class' => '\admin\filter\LoginFilter',
     'exclude' => '/admin/login',
 ]
```

以上代码拦截所有 以 `/admin/` 开头的 `URL` 地址, 比如: 
- `http://example.com/index.php?g=admin/index`  
- `http://example.com/index.php/admin/index` 
- `http://example.com/admin/index` (PATHINFO 并且开启 url_rewrite).

*class* 过滤器类
*exclude* 配置可以是数组也可以是正则表达式, 如果是数组，那么每个数组向均是正则表达式，代表此过滤器不拦截的 `URL` 地址规则.

> 过滤器按照定义的顺序执行

### 2.2 过滤器类
过滤器需要继承 `\hymie\filter\Filter` 类, 并实现 `doFilter()` 方法。

1. 方法中可以使用 `R($to)` 方法跳转页面, 也可清理数据、设置环境参数等操作。

2. 如果过滤器不跳转地址，那么如果通过则必须返回 `true` 代表通过并继续执行下一个过滤器，否则返回 `\hymie\Result` 跳转到相应视图。

### 2.3 实现过滤器

```
<?php
namespace web\filter;

class SomeFilter extends \hymie\filter\Filter
{
    public function doFilter()
    {
        $checkResult = doSomeBusinessCheck();
        if ($checkResult == true) {
            return true;
        } else {
            $data = ["error" => "err message", "data" => "somedata"]
            return result()->addArray($data)->setView("error/filter_error");
        }
    }
}
```