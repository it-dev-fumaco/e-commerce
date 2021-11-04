<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql';
    protected $table = 'fumaco_shipping_service';
    protected $primaryKey = 'shipping_service_id';
}
