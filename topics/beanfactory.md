Hymie phpmvc 框架的 bean 工厂设计初衷是为了可以通过配置的方式集成三方框架、类库。而不是为了实现与 `springframework` 一样的 `IOC` 容器，不过目前的 bean 工厂实现的确具备某些 `springframework IOC` 容器的特性。

## 帮助函数



`get_bean` 函数是 bean 工厂的帮助函数，代码中直接使用该函数即可，函数原型为：

    function get_bean($name, $params = null, $isSingleton = false)

参数说明：

| 参数              | 说明              |
| ---------------- | ----------------  |
| `$name`          | bean 名 或者 类名（需有命名空间） |
| `$params`        | 如果 $name 是 bean 名，此参数无意义。 否则是类的构造函数参数 |
| `isSingleton`    | 如果 $name 是 bean 名，此参数无意义。 否则代表是否获取单例实例 |

> **bean 工厂通过配置的 _"bean 名"_ 创建的实例在单个 http 请求中是单例的，对于不同的 http 请求则不是单例的。**

> **bean 工厂通过 _"类名"_ 创建类实例，默认在单个 http 请求中不是单例的，可以在调用时指定是否需要单例实例，对于不同的 http 请求则不是单例的。**

## 一、bean 配置信息
### 1.1 配置文件

1. bean 工厂 的配置文件为 `[web_root]/config.bean.php` 文件, 如文件存在则应用启动时会包含该文件，配置文件会在 `$GLOBALS` 全局变量中保存键名为 `_beans` 的数组，bean 工厂使用这个数组进行 bean 生产工作。 

2. 也可以通过编程的方式传入配置数组给 bean 工厂，不过这种方式主要用户单元测试使用。

## 二、 定义 bean

bean 定义的属性说明，具体用法请参考下面示例。

| 参数                       | 说明                                           |
| ------------------------- | ---------------------------------------------- |  
| `bean-name`               | 必须唯一                                        |
| `class`                   | 是必须的                                        |
| `construct-arg`           | 如果类的构造函数需要参数（没有默认值的参数），则必须。  |
| `props`                   | 如需设置属性可配置此项                             |
| `functions`               | 如对象创建后需要执行初始化函数，则需要此项            |
| `factory-class`           | 工厂类名，必须                                   |
| `factory-method`          | 工厂类静态方法名，必须                             |
| `factory-method-args`     | 工厂类静态方法参数，如有则必须                      |

### 2.1  通过构造函数

不支持静态初始化函数，如需此种方式则参考**通过工厂方法**章节。

```
    // bean-name 必须唯一，否则后定义的配置会覆盖先定义的配置
    $beans['bean-name'] = [
        // 包含 namespace 的类名
        'class' => 'beans\MockBean',
        // 构造函数
        'construct-args' => [
            'arg1' => 123,
            'value2'
        ],
        //实例属性, 私有属性也可以赋值，属性名是必须的
        'props' => [
            'prop_name' => 'prop_value'
        ],
        //实例创建好后需要执行的函数
        'functions' => [
            // pushHanler 是方法名，['handler', 12, 23] 方法参数
            'pushHandler' => ['handler', 12, 23],

            //init 是方法名，null 代表该方法没有参数
            'init' => null
        ]
    ]
```

### 2.2 工厂方法

工厂方法要注意的是

* `props` 是指工厂方法生成的 bean 实例的属性，而不是工厂类的属性。
* `functions` 是指工厂方法生成的 bean 实例的方法，而不是工厂类的方法。

```
    $beans['mockbean'] = [
        'factory-class' => 'beans\MockBean',
        'factory-method' => 'getInstance',
        'factory-method-args' => [
            'propa' => 4,
            'propb' => 5
        ],
        //工厂方法生成的实例属性, 私有属性也可以赋值
        'props' => [
            'propa' => 6,
            'propb' => 7
        ],
        //工厂方法生成的实例创建好后需要执行的函数
        'functions' => [
            // pushHanler 是方法名，['handler', 12, 23] 方法参数
            'pushHandler' => ['handler', 12, 23],

            //init 是方法名，null 代表该方法没有参数
            'init' => null
        ]
    ];
```

> 注: 工厂方法必须是静态方法

## 三、 bean 依赖

bean 工厂配置中使用 `ref:[bean-name]` 的方式定义 bean 间的依赖关系。可以在属性、参数（构造函数、方法）上定义 bean 间的依赖关系。

假设有以下 bean 定义

```
    $beans['refb'] = [
        'class' => 'beans\RefB'
    ]
```

### 3.1 通过构造函数定义依赖关系
```
    // 通过构造函数定义依赖关系，生产 refa1 实例前会先生产 refb 的实例
    $beans['refa1'] = [
        'class' => 'beans\RefA',
        'construct-args' => [
            'refb' => 'ref:refb'
        ]
    ];
```

### 3.2 通过工厂方法定义依赖关系
```
    // 通过共超过你定义依赖关系，生产 refa2 实例前会先生产 refb 的实例
    $beans['refa2'] = [
        'factory-class' => 'beans\RefB',
        'factory-method' => 'getInstance',
        'factory-method-args' => [
            'refb' => 'ref:refb'
        ]
    ];
```

### 3.3 通过属性定义依赖关系
```
    // 通过属性定义依赖关系，refa3 实例生产出来后生产 refb 再讲 refb 赋值给属性
    $beans['refa3'] = [
        'class' => 'beans\RefC',
        'props' => [
            'refb' => 'ref:refb'
        ]
    ];
```
### 3.4 通过函数参数
```
    // 通过属性定义依赖关系，refa3 实例生产出来后生产 refb 再讲 refb 赋值给属性
    $beans['refa4'] = [
        'class' => 'beans\RefD',
        'functions' => [
            'refb' => 'ref:refb'
        ]
    ];
```

## 四、循环依赖
循环依赖是指 beanA 依赖 beanB, 同时 beanB 也依赖 beanA, 因此在定义 bean 时需要注意循环依赖问题

### 4.1 错误的定义方式

1. BeanA 和 BeanB 不能同时使用 _"构造函数参数"_ 定义依赖关系，比如：
```
    // php 会提示无限递归，最终耗尽栈空间
    $beans['cyca'] = [
        'class' => 'beans\CycleA',
        'construct-args' => [
            'cycleB' => 'ref:cycb'
        ]
    ];

    $beans['cycb'] = [
        'class' => 'beans\CycleB',
        'construct-args' => [
            'cycleA' => 'ref:cyca'
        ]
    ];
```
2. BeanA 和 BeanB 不能同时使用 _"工厂方法参数"_ 定义依赖关系，比如
```
    // php 会提示无限递归，最终耗尽栈空间
    $beans['cyca'] = [
        'factory-class' => 'beans\CycleA',
        'factory-method-args' => [
            'cycleB' => 'ref:cycb'
        ]
    ];

    $beans['cycb'] = [
        'factory-class' => 'beans\CycleB',
        'factory-method-args' => [
            'cycleA' => 'ref:cyca'
        ]
    ];
```
3. BeanA 和 BeanB 不能使用 _"构造函数-工厂方法"_ 或 _"工厂方法-构造函数"_ 的方式定义依赖关系，比如
```
    // php 会提示无限递归，最终耗尽栈空间
    $beans['cyca'] = [
        'class' => 'beans\CycleA',
        'construct-args' => [
            'cycleB' => 'ref:cycb'
        ]
    ];

    $beans['cycb'] = [
        'factory-class' => 'beans\CycleB',
        'factory-method-args' => [
            'cycleA' => 'ref:cyca'
        ]
    ];
```

### 4.2 正确的定义方式

除了 4.1 中错误的方式，其他任何方式都可以正确定义循环依赖，比如以下示例：

```
// cyca 构造函数依赖 cycb，cycb 属性依赖 cyca
    $beans['cyca'] = [
        'class' => 'beans\CycleA',
        'construct-args' => [
            'cycleB' => 'ref:cycb'
        ]
    ];

    $beans['cycb'] = [
        'factory-class' => 'beans\CycleB',
        'props' => [
            'cycleA' => 'ref:cyca'
        ]
    ];

// cyca 属性依赖 cycb，cycb 工厂方法依赖 cyca
    $beans['cyca'] = [
        'class' => 'beans\CycleA',
        'props' => [
            'cycleB' => 'ref:cycb'
        ]
    ];

    $beans['cycb'] = [
        'factory-class' => 'beans\CycleB',
        'factory-method-args' => [
            'cycleA' => 'ref:cyca'
        ]
    ];

// cyca 函数参数依赖 cycb，cycb 工厂方法依赖 cyca
    $beans['cyca'] = [
        'class' => 'beans\CycleA',
        'functions' => [
            'cycleB' => 'ref:cycb'
        ]
    ];

    $beans['cycb'] = [
        'factory-class' => 'beans\CycleB',
        'factory-method-args' => [
            'cycleA' => 'ref:cyca'
        ]
    ];

//cyca 属性依赖 cycb，cycb 属性依赖 cyca
    $beans['cyca'] = [
        'class' => 'beans\CycleA',
        'props' => [
            'cycleB' => 'ref:cycb'
        ]
    ];

    $beans['cycb'] = [
        'class' => 'beans\CycleB',
        'props' => [
            'cycleA' => 'ref:cyca'
        ]
    ];

//cyca 函数参数依赖 cycb，cycb 属性依赖 cyca
    $beans['cyca'] = [
        'class' => 'beans\CycleA',
        'functions' => [
            'cycleB' => 'ref:cycb'
        ]
    ];

    $beans['cycb'] = [
        'class' => 'beans\CycleB',
        'props' => [
            'cycleA' => 'ref:cyca'
        ]
    ];
```
### 3.4 通过类名创建对象
可以使用 `get_bean($name, $param = null, $isSingleton = false)` 函数或者 `\hymie\BeanFactory::getInstance()->getBean($name, $param = null)` 直接通过类名构造类实例。实例:

```
//获取 \PDO 对象，非单例
$bean = get_bean('\PDO', ['mysql:dbname=gszx;host=127.0.0.1', 'root', 'password' => '123456']);

//获取单例的 \PDO 对象
$bean = get_bean('\PDO', ['mysql:dbname=gszx;host=127.0.0.1', 'root', 'password' => '123456'], true);

//获取单例其他对象, 无构造函数参数
$beanOther = get_bean('\SomeOtherClass', null, true);
```

> 注: 通过类名创建的对象默认不是单例的, 通过 beans 配置创建的对象默认是单例的。

### 3.5 预定义 bean 名称
  以下 bean 名称已被框架使用，在 `config.bean.php` 文件中已有默认示例。
  
  1. `cache`  
    `\hymie\Cache` 会默认寻找名为 `cache` 的缓存 bean 配置，若未找到默认使用 `ArrayCache`。

  2. `twig_loader`  
    默认是 `\Twig\Loader\FilesystemLoader`，twig bean 初始化时必须依赖的组件。

  3. `twig`  
    `\hymie\view\TwigView` 默认会寻找这个名称的 bean，如果不使用 twig 模板引擎则可不配置。
  
  4. `predis`    
    `\hymie\session\RedisSession` 会用到该名称的 `Predis` 需要在  `config.bean.php`中完善这个 bean 的配置。

  5. `logger`  
    日志对象 bean，需配置符合 psr log 规范的日志框架实现
    