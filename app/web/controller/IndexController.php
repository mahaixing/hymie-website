<?PHP
namespace web\controller;

class IndexController
{
    public function index()
    {
        return result()->setView("index");
    }
}