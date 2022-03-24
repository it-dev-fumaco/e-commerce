<?php

namespace App\Http\Traits;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait GeneralTrait {
    public function generateShortUrl($root, $url) {
        $random_code = Str::random(6);
        $generated_code = $this->generateUniqueCode($random_code);

        DB::table('fumaco_short_links')->insert([
            'url' => $url,
            'code' => $generated_code,
        ]);

        return $root . '/' . $generated_code;
    }

    private function generateUniqueCode($code) {
        $existing_code = DB::table('fumaco_short_links')->where('code', $code)->exists();
        if (!$existing_code) {
            return $code;
        } else {
            return $this->generateUniqueCode(Str::random(6));
        }
    }
}