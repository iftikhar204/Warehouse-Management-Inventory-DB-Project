<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'Emp_ID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'F_Name',
        'L_Name',
        'DoB',
        'hire_date',
        'department',
        'salary',
        'position',
        'address_ID',
        'termination_date',
    ];

    protected $dates = ['DoB', 'hire_date', 'termination_date'];

    public function address()
    {
        return $this->hasOne(Address::class, 'address_ID', 'address_ID');
    }

    public function contactNumbers()
    {
        // Pivot table: 'employee_contacts'
        // Foreign key on pivot for this Employee model: 'Emp_ID' (matches primary key of Employee)
        // Foreign key on pivot for the related ContactNumber model: 'contact_ID'
        return $this->belongsToMany(ContactNumber::class, 'employee_contacts', 'Emp_ID', 'contact_ID');
    }

    public function supervisor()
    {
        // An Employee can be a Supervisor (hasOne Supervisor record for this Emp_ID)
        return $this->hasOne(Supervisor::class, 'Emp_ID', 'Emp_ID');
    }

    public function orderProcessor()
    {
        return $this->hasOne(OrderProcessor::class, 'Emp_ID', 'Emp_ID');
    }

    public function inventoryClerk()
    {
        return $this->hasOne(InventoryClerk::class, 'Emp_ID', 'Emp_ID');
    }

    public function shippingClerk()
    {
        return $this->hasOne(ShippingClerk::class, 'Emp_ID', 'Emp_ID');
    }

    // --- Relationships for 'supervisions' pivot table ---

    // Relationship to get *this employee's* supervisor(s).
    // An Employee (Emp_ID) is supervised by a Supervisor (Supervisor_ID) via the 'supervisions' pivot table.
    public function mySupervisor()
    {
        // The foreign key on 'supervisions' referring to *this* (supervised) employee is 'Emp_ID'.
        // The foreign key on 'supervisions' referring to the related Supervisor is 'Supervisor_ID'.
        // The last two arguments are the local key on this model (Employee) and the local key on the related model (Supervisor).
        // Since both Employee and Supervisor models use 'Emp_ID' as their primary key, these are 'Emp_ID'
        return $this->belongsToMany(Supervisor::class, 'supervisions', 'Emp_ID', 'Supervisor_ID', 'Emp_ID', 'Emp_ID');
    }

    // Relationship to get *employees supervised by this employee* (if this employee is a supervisor).
    // An Employee (acting as a supervisor, via Supervisor_ID) supervises other Employees (Emp_ID) via 'supervisions'.
    public function employeesSupervised()
    {

        return $this->belongsToMany(Employee::class, 'supervisions', 'Supervisor_ID', 'Emp_ID', 'Emp_ID', 'Emp_ID');
    }
}
