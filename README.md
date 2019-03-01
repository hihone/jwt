# jwt for lavavel 

## 安装
只要在你的 `composer.json` 文件require中加入下面内容，就能获得最新版.

~~~
composer require hihone/jwt:dev-master
~~~


然后需要运行 "composer update" 来更新你的项目

安装完后，生成 `config/jwt.php` 配置文件，

~~~
php artisan vendor:publish --provider="hihone\jwt\JWTServiceProvider" 
~~~

## 使用
~~~
//        JWT::payload([
//            'name' => 'hihone',
//            'sub' => '测试'
//        ]);
        dd(JWT::getToken());
#eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.W10.2Ue8Tp6Yh59Q8ioRlqz20USnswttxZ1KCeA99QFFLS4

JWT::payload([
    'name' => 'hihone',
    'sub' => '测试'
]);
dd(JWT::getToken());
#eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOlwvXC9kZW1vLnRlc3QiLCJleHAiOjE1NTE0MzQ4NDgsInN1YiI6Iua1i-ivlSIsImF1ZCI6Imh0dHA6XC9cL2RlbW8udGVzdCIsIm5iZiI6MTU1MTQyNzY3OCwiaWF0IjoxNTUxNDI3NjQ4LCJqdGkiOiJKV1QxNTUxNDI3NjQ4NWM3OGU4NDBjZDk4MSIsIm5hbWUiOiJoaWhvbmUifQ.gDOpBG0PFFBAU2ObpSVZ0ZbAgN0QN2BiGonUUrV6BRw
~~~

## 验证 token
~~~
$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwOlwvXC9kZW1vLnRlc3QiLCJleHAiOjE1NTE0MzQ4NDgsInN1YiI6Iua1i-ivlSIsImF1ZCI6Imh0dHA6XC9cL2RlbW8udGVzdCIsIm5iZiI6MTU1MTQyNzY3OCwiaWF0IjoxNTUxNDI3NjQ4LCJqdGkiOiJKV1QxNTUxNDI3NjQ4NWM3OGU4NDBjZDk4MSIsIm5hbWUiOiJoaWhvbmUifQ.gDOpBG0PFFBAU2ObpSVZ0ZbAgN0QN2BiGonUUrV6BRw';
$payload = JWT::verify($token);
捕获 JWTException 异常，
~~~