<?php
/**
 *
 *
 * User: hihone
 * Date: 2019/3/1
 * Time: 15:56
 * Description:
 */

namespace hihone\jwt;


use Illuminate\Support\ServiceProvider;

class JWTServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $configPath = __DIR__ . '/../config/jwt.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('jwt.php');
        } else {
            $publishPath = base_path('config/jwt.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }

    public function register()
    {
        $this->app->singleton('taxi', function () {
            return new JWT;
        });
    }
}