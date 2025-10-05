<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkStorage extends Model
{
    protected $table = 'bulk_storages';

    protected $primaryKey = 'Sub_section_ID';
    public $incrementing = false;

    protected $fillable = [
        'Sub_section_ID', 'Weight_Measurement', 'max_weight'
    ];

    public function subsection()
    {
        return $this->belongsTo(Subsection::class, 'Sub_section_ID');
    }
}

