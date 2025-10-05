<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColdStorage extends Model
{
    protected $table = 'cold_storages';

    protected $primaryKey = 'Sub_section_ID';
    public $incrementing = false;

    protected $fillable = [
        'Sub_section_ID', 'Freezer_Sec_Assigned', 'min_temperature', 'max_temperature'
    ];

    public function subsection()
    {
        return $this->belongsTo(Subsection::class, 'Sub_section_ID');
    }
}
