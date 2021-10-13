<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCondition extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'fumaco_shipping_condition';
    protected $primaryKey = 'shipping_condition_id';
}
