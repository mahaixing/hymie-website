Hymie 框架提供一些帮助参数，这些函数在全局命名空间中，可以直接使用。函数手册请参考 [API 手册](http://hymie.iautoo.cn/docs/index.html)

- `R($to)`  
  跳转到地址, 地址如果以 `http` 开头, 则直接跳转, 否则会根据 `config.php` 中 `url` 配置规则生成 url

- `_U($path, $params = null, $script_name = '')`    
  生成地址, 并返回地址字符串

- `U($path, $params = null, $script_name = '')`  
  生成地址, 直接输出

- `result`  
  返回 `\hymie\Result()` 对象实例

- `get_type($target)`  
  返回对象、变量类型

- `context_path()`  
  返回当前路径上下文

- `get_bean($name, $params=null)`  
  工厂函数, 根据名字获取在 `config.bean.php` 中配置的 bean

- `get_config($key, $default='')`  
  读取配置，支持`['url', 'url_rewrite']` 或者 `a/b/c` 这种方式读取子配置，当配置不存在时返回 `$default` 值

- `get_array_item($array, $key, $default = null)`  
  获取数组项的值, 若不存在则返回默认值

- `get_logger()`  
  获取当前日志对象的帮助函数

- `log_info($msg, $extra=[])`  
  info 日志

- `log_warning($msg, $extra=[])`  
  warning 日志

- `log_error($msg, $extra=[])`  
  error 日志

- `log_debug($msg, $extra=[])`  
  debug 日志

- `really_exists($array, $key)`  
  数组中指定键是否存在, 值是否被设置(`isset` 为true)

- `set_cookie($name, $value = '', $expire = 0, $path = '')`  
  设置 cookie

- `start_session()`  
  如果 `config.php` 中配置了 redis session 使用 redis session, 否则使用 php 原生 session. **建议使用此函数来启动session, 而不是内置的 [`session_start`](https://www.php.net/session_start) 函数**

- `now($time = null, $long = true)`  
  获取时间, 格式为 2019-08-01 20:01:01 或者 2019-08-01

- `http_404($msg)`  
  返回 404, 简便方法, 内部调用 `set_status_header` 函数

- `http_500($msg)`  
  返回 500, 简便方法, 内部调用 `set_status_header` 函数

- `set_status_header($code = 200, $text = '')`
  返回 http 头, 默认 200