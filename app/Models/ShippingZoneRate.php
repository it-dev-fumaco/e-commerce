<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZoneRate extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'fumaco_shipping_zone_rate';
    protected $primaryKey = 'shipping_zone_rate_id';
}
