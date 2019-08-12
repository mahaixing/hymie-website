<?PHP
/**
 * 模块路由配置，使用正则配置，参考如下元字符
 * \d       任意十进制数字
 * \D       任意非十进制数字
 * \h       任意水平空白字符(since PHP 5.2.4)
 * \H       任意非水平空白字符(since PHP 5.2.4)
 * \s       任意空白字符
 * \S       任意非空白字符
 * \v       任意垂直空白字符(since PHP 5.2.4)
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
 * '/' => 'web\controller\IndexController:index',
 *
 * @see https://www.php.net/manual/zh/book.pcre.php
 */
return array(
    // IndexController->someMethod
    '/' => 'web\controller\IndexController',
);