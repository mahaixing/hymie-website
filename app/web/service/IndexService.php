<?PHP

namespace web\service;

use web\data\TopicData;

class IndexService
{
    const TOPIC_ROOT = ROOT . DIRECTORY_SEPARATOR . 'topics' . DIRECTORY_SEPARATOR;

    public function getTopic($topic)
    {
        $topicInfo = TopicData::getTopic($topic);
        
        if ($topicInfo == null) {
            return null;
        }

        $filename = self::TOPIC_ROOT . $topicInfo['file'];
        if (! file_exists($filename)) {
            http_404("topic does not exist.");
        }
        
        $content = \file_get_contents($filename);
        // echo $content;

        $parseDown = new \Parsedown();

        return [
            'topic' => $topicInfo['topic'],
            'title' => $topicInfo['title'], 
            'content' => $parseDown->text($content)
        ];
    }
}