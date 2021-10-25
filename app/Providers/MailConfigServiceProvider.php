<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use DB;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $email_config = DB::table('email_config')->first();

        if ($email_config) {
            $config = array(
                'driver' => $email_config->driver,
                'host' => $email_config->host,
                'port' => $email_config->port,
                'encryption' => $email_config->encryption,
                'username' => $email_config->username,
                'password' => $email_config->password,
                'from' => [
                    'address' => $email_config->address,
                    'name' => $email_config->name,
                ],
                'timeout' => null,
                'auth_mode' => null,
            );

            Config::set('mail', $config);
        }
    }
}