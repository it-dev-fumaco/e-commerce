<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class DefaultMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('migrations')->insert([
            [
                'migration' => '2014_10_12_000000_create_users_table',
                'batch' => 1
            ],
            [
                'migration' => '2014_10_12_100000_create_password_resets_table',
                'batch' => 1
            ],
            [
                'migration' => '2019_08_19_000000_create_failed_jobs_table',
                'batch' => 1
            ],
            [
                'migration' => '2019_12_14_000001_create_personal_access_tokens_table',
                'batch' => 1
            ]
        ]);
    }
}
