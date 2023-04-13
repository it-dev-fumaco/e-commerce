<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use DB;
use Cache;

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
        // 
        $config = Cache::remember('mail_config', 3600, function () {
            $email_config = DB::table('email_config')->first();
            if ($email_config) {
                return $config = array(
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
                    // 'stream' => [
                    //     'ssl' => [
                    //         'allow_self_signed' => true,
                    //         'verify_peer' => false,
                    //         'verify_peer_name' => false,
                    //     ],
                    //     'tls' => [
                    //         'verify_peer' => false,
                    //         'verify_peer_name' => false,
                    //         'allow_self_signed' => true,
                    //     ],
                    // ],
                );
            }
        });

        Config::set('mail', $config);
        
    }
}