<?php

namespace web\controller;

use hymie\annotation\RouterMapping;

/**
 * @RouterMapping(value="/(\w*)")
 */
class IndexController
{
    public function index($topic = 'main')
    {
        $service = new \web\service\IndexService();
        $data = $service->getTopic($topic);

        $request = \hymie\Request::getInstance();

        if ($data == null) {
            R('/');
        } else {
            if ($request->isMobile()) {
                return result()->setView('index_mobile')->success($service->getTopic($topic));
            } else {
                return result()->setView('index')->success($service->getTopic($topic));
            }
        }
    }
}
