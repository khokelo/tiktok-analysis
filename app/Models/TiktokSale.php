<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiktokSale extends Model
{
    protected $fillable = [
    'campaign',
    'date',
    'time',
    'direct_gmv',
    'items_sold',
    'customers',
    'viewers'
];

}
