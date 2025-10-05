<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Includes extends Model
{
    protected $table = 'includes';
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_ID', 'warehouse_ID');
    }
}
