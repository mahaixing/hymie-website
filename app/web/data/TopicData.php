<?PHP

namespace web\data;

class TopicData
{

    private static $topics = [
        'main' => ['title' => '简介', 'file' => 'main.md'],
        'beanfactory' => ['title' => 'Bean 工厂', 'file' => 'beanfactory.md'],
        'cache' => ['title' => '缓存', 'file' => 'cache.md'],
        'config' => ['title' => '配置', 'file' => 'config.md'],
        'fileupload' => ['title' => '文件上传', 'file' => 'fileupload.md'],
        'helpers' => ['title' => '帮助函数和类', 'file' => 'helpers.md'],
        'logger' => ['title' => '日志', 'file' => 'logger.md'],
        'mvc' => ['title' => 'MVC', 'file' => 'mvc.md'],
        'pagniation' => ['title' => '分页', 'file' => 'pagniation.md'],
        'routerandfilter' => ['title' => '路由及过滤器', 'file' => 'routerandfilter.md'],
        'server' => ['title' => '服务器配置', 'file' => 'server.md'],
        'session' => ['title' => 'Session', 'file' => 'session.md'],
        'unittest' => ['title' => '单元测试', 'file' => 'unittest.md']
        // 'install' => ['title'=>'安装', 'file'=>'install.md']
    ];

    public static function getTopic($topic)
    {
        if (array_key_exists($topic, self::$topics)) {
            $topicKey = $topic;
            $topicInfo = self::$topics[$topicKey];
            $topicInfo = array_merge($topicInfo, ['topic' => $topicKey]);
            return $topicInfo;
        } else {
            return null;
        }
    }
}
