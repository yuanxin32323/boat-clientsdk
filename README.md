# What's This?
Boat2网络验证平台 开发者接口SDK（PHP）。

### 获取实例
```php

/**
 * 测试用例
 * composer调用法
 */
require './vendor/autoload.php';

//实例化SDK类
$open_id = '2e2248ed9ba548309c4dee4a921973ad';
$secret = '9kgefopouqz1ygsndhwdl1jwn41a8xm9';
$api = new Boat\Dev\Api($open_id, $secret);


//查询授权
print_r($api->queryAuth('123'));

```

