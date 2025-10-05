<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HazardousMaterials extends Model
{
    protected $table = 'hazardous_materials';

    protected $primaryKey = 'Sub_section_ID';
    public $incrementing = false;

    protected $fillable = [
        'Sub_section_ID', 'Hazardous_Class', 'safety_level'
    ];

    public function subsection()
    {
        return $this->belongsTo(Subsection::class, 'Sub_section_ID');
    }
}
