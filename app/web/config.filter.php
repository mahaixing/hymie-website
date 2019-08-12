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
    //     'class' => '\admin\filter\LoginFilter',
    //     'exclude' => '/login',
    // ]

);