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
$key = <<<str
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvi2H/QM0YDeI8BL82mZg7twSf
2xZ+dz6kIc3KlcUDVOazzAcCNkTfsgQwxn0hEev5M1lJyZtkSCOyxrjN50rfZTM9
ROkltA2+P3EDuRm7MVJjCSi3RGwb693/eWcemE6ehP24sNPewJsjfsMENxEa4uhL
1DHutBwuqEP9CnmYewIDAQAB
-----END PUBLIC KEY-----
str;
$api = new Boat\Client\Api($open_id, $key);



//查询授权
print_r($api->create());

print_r($api->login('1121'));
 

```

