<?PHP

namespace web\controller;

class IndexController
{
    public function index($topic = 'main')
    {
        $service = new \web\service\IndexService();
        $data = $service->getTopic($topic);

        if ($data == null) {
            R('/');
        } else {
            return result()->setView("index")->success($service->getTopic($topic));
        }
    }
}
