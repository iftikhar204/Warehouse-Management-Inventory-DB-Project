<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $primaryKey = 'Emp_ID'; // Supervisor's primary key is the same as Employee's Emp_ID
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Emp_ID',
        'team_size',
        'department', // Assuming department is also in supervisors table based on your controller
    ];

    // A Supervisor record belongs to an Employee record
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_ID', 'Emp_ID');
    }

    // A Supervisor supervises many Employees
    public function supervisedEmployees()
    {

        return $this->belongsToMany(Employee::class, 'supervisions', 'Supervisor_ID', 'Emp_ID', 'Emp_ID', 'Emp_ID');
    }
}
