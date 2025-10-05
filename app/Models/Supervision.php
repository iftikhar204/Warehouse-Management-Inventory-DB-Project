<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    protected $table = 'supervisions';

    protected $fillable = [
        'Emp_ID', 'Supervisor_ID', 'relationship_type', 'start_date', 'end_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_ID');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'Supervisor_ID');
    }
}

