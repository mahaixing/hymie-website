Hymie PHPMVC 框架的分页模块采用适配器模式实现，目前支持 `PDO` 分页以及 `Medoo` 数据库框架分页。其中 `PDO` 目前支持 `mysql` 数据库分页，其他数据库分页后续会完成开发。

## 一、获取分页类
1. `\hymie\pager\Pager`
  使用该类的工厂方法 `public static function getPager($adapterObjOrClassName, ...$params)` 创建具体分页适配器.

  - `$adapterObjOrClassName`   
    1. `\PDO` 或者 `Medoo\Medoo` 实例
    2. 类名用于构造用户自定义的分页适配器

  - `$params`  
   适配器的构造函数参数, 具体参考适配器构造函数

2. 分页方法 `$pager->doPage($page, $pageSize)`

  - `$page` 当前页  
    默认分页类是从 `$_GET`、`$_POST`、`$_REQUEST` 全局数组中获取分页参数, 单也可以在工厂方法中传递分页参数, 这通常适用于 PathVariable 类型的分页.

  - `$pageSize` 每页数据条数  
    默认分页类是从 `$_GET`、`$_POST`、`$_REQUEST` 全局数组中获取分页参数, 单也可以在工厂方法中传递分页参数, 这通常适用于 PathVariable 类型的分页. 默认 10

  > `doPage` 方法执行完后，会在 `$_REQUEST` 数组中保存自己的实例, `$key` 值为 `_pager`

## 二、分页适配器  

### 2.1 系统原生的适配器有

  - `\hymie\pager\adapter\PdoPagerAdapter` (已实现)  

    PDO 分页, 此分页类还使用 `\hymie\pager\adapter\dialect` 命名空间下的分页数据库 dialect 类.
      - `\hymie\pager\adapter\dialect\MysqlPdoDialect` mysql分页方言 (已实现)
      - `\hymie\pager\adapter\dialect\OciPdoDialect` oracle 分页方言 (暂未实现)
      - `\hymie\pager\adapter\dialect\PgsqlPdoDialect` postgreSQL 分页方言 (暂未实现)
      - `\hymie\pager\adapter\dialect\SqlsrcPdoDialect` SQLServer 分页方案 (暂未实现)

    PDO 分页适配器会根据 PDO 的链接属性选择正确的分页方言类

  - `\hymie\pager\adapter\MedooPageAdapter` (已实现)  
    Medoo 分页类, 
    > 如果想使用 `Medoo::query` 函数分页，建议使用如下方式  
    > ```
    > $medoo = get_bean('medoo');
    > $sql = "select * from area where levelNum like ? order by areaNum desc";
    > $pager = \hymie\pager\Pager::getPager($medoo->pdo, $sql, '11%', [PDO::FETCH_CLASS, "\\beans\\Area"]);
    > ```
    > 因为如果使用 `Medoo::query` 方法分页，实际上使用了与数据库依赖的语句，因此直接获取 `Medoo::$pdo` 对象用 `PdoPageAdapter` 分页即可

### 2.2 分页示例

  ```
    // config.bean.php
    $beans = array();
    $beans['pdo'] = [
        'class' => 'PDO',
        'construct-args' => [
            'dsn' => 'mysql:dbname=gszx;host=127.0.0.1',
            'user' => 'root',
            'password' => '123456',
            [\PDO::ATTR_PERSISTENT => true]
        ]
    ];

    $beans['medoo'] = [
        'class' => 'Medoo\Medoo',
        'construct-args' => [
            ['database_type' => 'mysql',
            'database_name' => 'gszx',
            'server' => '127.0.0.1',
            'username' => 'root',
            'password' => '123456']
        ]
    ];

    $GLOBALS['_beans'] = &$beans;

    // some_service.php

    $pdo = get_bean('pdo');
    $sql = "select * from area where levelNum like ? order by areaNum desc";
    $pager = \hymie\pager\Pager::getPager($pdo, $sql, '11%', [PDO::FETCH_CLASS, "\\beans\\Area"]);
    $pager->doPage();
    $data = $pager->getData();

    // some_other_service.php
    $medoo = get_bean('medoo');
    $pager = \hymie\pager\Pager::getPager($medoo, 'area', '*');
    $pager->doPage(15);
    $data = $pager->getData();
  ```

> 更多的实例请参考 tests/hymie/PagerTest.php

## 三、实现自己的分页适配器  
  通过实现 `hymie\pager\adapter\PagerAdapterInterface` 接口来实现自己的分页适配器, 在调用 `\hymie\pager\Pager::getPager($adapterObjOrClassName, ...$params)` 方法时需要使用类名(包含命名空间)

  ```
  // 自定义分页适配器
  <?PHP
    namespace myproject\pager;

    class ArrayPagerAdapter implements PagerAdapterInterface
    {
        private $array;
        public function __construct()
        {
            $array = [];
            for ($i = 0; $i < 1000; $i++) {
                $array[] = $i;
            }

            $this->setArray($array);
        }


        public function getDataCount()
        {
            return count($this->array);
        }

        public function getData($page, $pageSize)
        {
            return array_slice($this->array, $page * $pageSize, $pageSize);
        }

        public function getArray()
        {
            return $this->array();
        }

        public function setArray($value)
        {
            $this->array = $value;
        }
    }
  ?>

  <?PHP

    class SomeService 
    {
        public function doSome()
        {
            $pager = \hymie\Pager::getPager("myproject\pager\ArrayPagerAdapter");

            $pager->doPage(1);
            return $pager->getData();
        }

    }
  ?>

  ```