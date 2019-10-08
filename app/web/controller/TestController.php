<?php

namespace web\controller;

use hymie\annotation\RouterMapping;

/**
 * @RouterMapping(value="/cache");
 */
class TestController
{
    /**
     * @RouterMapping(value="apcu1")
     */
    public function apcu()
    {
        echo '<pre>';

        $obj = apcu_cache_info();

        var_dump($obj);
    }

    /**
     * @RouterMapping(value="file1")
     */
    public function file()
    {
        echo '<pre>';

        $cache = \hymie\cache\Cache::getInstance('\Symfony\Component\Cache\Adapter\FilesystemAdapter');
        $obj = $cache->get('framework.cache.need.clean.keys');

        var_dump($obj);
    }

    /**
     * @RouterMapping(value="clean")
     */
    public function clean()
    {
        apcu_clear_cache();
        $cache = \hymie\cache\Cache::getInstance('\Symfony\Component\Cache\Adapter\FilesystemAdapter');
        $cache->clear();
    }
}
