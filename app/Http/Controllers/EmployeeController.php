<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Address;
use App\Models\ContactNumber;
use App\Models\Supervisor;
use App\Models\OrderProcessor;
use App\Models\InventoryClerk;
use App\Models\ShippingClerk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['mySupervisor.employee', 'address', 'contactNumbers'])->get();

        $roleCounts = [
            'Order Processor' => Employee::where('position', 'Order Processor')->count(),
            'Manager' => Employee::where('position', 'Manager')->count(),
            'Warehouse Staff' => Employee::where('position', 'Warehouse Staff')->count(),
            'Driver' => Employee::where('position', 'Driver')->count(),
            'Inventory Clerk' => Employee::where('position', 'Inventory Clerk')->count(),
            'Shipping Clerk' => Employee::where('position', 'Shipping Clerk')->count(),
            'Supervisor' => Employee::where('position', 'Supervisor')->count(),
        ];

        return view('employees.index', compact('employees', 'roleCounts'));
    }

    public function showDetails(Employee $employee)
    {
        // Eager load necessary relationships for the details modal
        // This is key: we need supervisor's employee details, employee's address, and all contact numbers
        $employee->load(['mySupervisor.employee', 'address', 'contactNumbers']);

        // Initialize variables for contacts and supervisor
        $email = null;
        $phone = null;
        $supervisorName = 'N/A';

        // Get email contact
        $emailContact = $employee->contactNumbers->firstWhere('contact_type', 'email');
        if ($emailContact) {
            $email = $emailContact->contact_value;
        }

        // Get phone/mobile contact (prioritize mobile if both exist)
        $mobileContact = $employee->contactNumbers->firstWhere('contact_type', 'mobile');
        $phoneContact = $employee->contactNumbers->firstWhere('contact_type', 'phone');

        if ($mobileContact) {
            $phone = $mobileContact->contact_value;
        } elseif ($phoneContact) {
            $phone = $phoneContact->contact_value;
        }

        // Get supervisor name
        if ($employee->mySupervisor->first() && $employee->mySupervisor->first()->employee) {
            $supervisorEmployee = $employee->mySupervisor->first()->employee;
            $supervisorName = $supervisorEmployee->F_Name . ' ' . $supervisorEmployee->L_Name;
        }

        return response()->json([
            'Emp_ID' => $employee->Emp_ID,
            'F_Name' => $employee->F_Name,
            'L_Name' => $employee->L_Name,
            'position' => $employee->position,
            'department' => $employee->department,
            'hire_date' => optional($employee->hire_date)->format('Y-m-d'),
            'termination_date' => optional($employee->termination_date)->format('Y-m-d'),
            'email' => $email, // Now extracted from contactNumbers
            'phone' => $phone, // Now extracted from contactNumbers
            'address' => $employee->address, // Send the full address object
            'notes' => $employee->notes ?? null,
            'supervisor_name' => $supervisorName, // Send the formatted supervisor name directly
        ]);
    }

    public function create()
    {
        $departments = Employee::select('department')->distinct()->pluck('department')->sort()->toArray();
        $defaultDepartments = ['Warehouse', 'Inventory', 'Shipping', 'Procurement', 'Management'];
        $departments = array_unique(array_merge($defaultDepartments, $departments));
        sort($departments);

        $unsupervisedEmployees = Employee::whereDoesntHave('supervisor')
            ->whereDoesntHave('mySupervisor')
            ->orderBy('F_Name')
            ->orderBy('L_Name')
            ->get();

        return view('employees.create', compact('departments', 'unsupervisedEmployees'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'F_Name' => 'required|string|max:255',
            'L_Name' => 'required|string|max:255',
            'DoB' => 'required|date',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state_province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'department' => 'required|string',
            'salary' => 'required|numeric',
            'position' => 'required|string|max:255',
            'team_size' => 'nullable|integer',
            'avg_processing_time' => 'nullable|numeric',
            'accuracy_rate' => 'nullable|numeric',
            'forklift_certification' => 'nullable|boolean',
            'items_processed' => 'nullable|integer',
            'error_rate' => 'nullable|numeric',
            'packages_per_day' => 'nullable|integer',
            'contacts' => 'required|array|min:1',
            'contacts.*.contact_type' => 'required|string|in:phone,mobile,fax,email',
            'contacts.*.contact_value' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $index = explode('.', $attribute)[1];
                    $contactType = $request->input("contacts.{$index}.contact_type");
                    if ($contactType === 'email') {
                        $query = ContactNumber::where('contact_value', $value)
                                             ->where('contact_type', 'email');
                        if ($query->exists()) {
                            $fail("The email address '{$value}' is already in use.");
                        }
                    }
                },
            ],
            'supervised_employees' => 'nullable|array',
            'supervised_employees.*' => 'exists:employees,Emp_ID',
        ]);

        DB::beginTransaction();

        try {
            $address = new Address([
                'street_address' => $validatedData['street_address'],
                'city' => $validatedData['city'],
                'state_province' => $validatedData['state_province'],
                'postal_code' => $validatedData['postal_code'],
                'country' => $validatedData['country'],
            ]);
            $address->save();

            $employee = new Employee([
                'F_Name' => $validatedData['F_Name'],
                'L_Name' => $validatedData['L_Name'],
                'DoB' => $validatedData['DoB'],
                'hire_date' => $validatedData['hire_date'],
                'department' => $validatedData['department'],
                'salary' => $validatedData['salary'],
                'position' => $validatedData['position'],
                'address_ID' => $address->getKey(),
            ]);
            $employee->save();

            switch ($validatedData['position']) {
                case 'Supervisor':
                    Supervisor::create([
                        'Emp_ID' => $employee->Emp_ID,
                        'team_size' => $validatedData['team_size'] ?? null,
                    ]);

                    if (isset($validatedData['supervised_employees'])) {
                        foreach ($validatedData['supervised_employees'] as $supervisedEmpId) {
                            DB::table('supervisions')->where('Emp_ID', $supervisedEmpId)->delete();
                            $employee->employeesSupervised()->attach($supervisedEmpId, [
                                'relationship_type' => 'direct',
                                'start_date' => Carbon::now(),
                            ]);
                        }
                    }
                    break;
                case 'Order Processor':
                    OrderProcessor::create([
                        'Emp_ID' => $employee->Emp_ID,
                        'Avg_time_Processing' => $validatedData['avg_processing_time'] ?? null,
                        'Accuracy_Rate' => $validatedData['accuracy_rate'] ?? null,
                    ]);
                    break;
                case 'Inventory Clerk':
                    InventoryClerk::create([
                        'Emp_ID' => $employee->Emp_ID,
                        'Forklift_Certification' => $request->has('forklift_certification'),
                        'Items_Processed_PerDay' => $validatedData['items_processed'] ?? null,
                    ]);
                    break;
                case 'Shipping Clerk':
                    ShippingClerk::create([
                        'Emp_ID' => $employee->Emp_ID,
                        'Error_Rate' => $validatedData['error_rate'] ?? null,
                        'Packages_PerDay' => $validatedData['packages_per_day'] ?? null,
                    ]);
                    break;
            }

            $newContactIds = [];
            if (isset($validatedData['contacts']) && is_array($validatedData['contacts'])) {
                foreach ($validatedData['contacts'] as $contactData) {
                    try {
                        $contact = ContactNumber::create([
                            'contact_type' => $contactData['contact_type'],
                            'contact_value' => $contactData['contact_value'],
                            'is_primary' => 0,
                        ]);
                        if ($contact && $contact->getKey() !== null) {
                            $newContactIds[] = (int) $contact->getKey();
                        } else {
                            Log::error("Failed to retrieve ID for newly created contact " . ($contactData['contact_value'] ?? 'unknown') . " during employee creation. Contact object: " . json_encode($contact));
                            throw new \Exception("Failed to process contact information for " . ($contactData['contact_value'] ?? 'unknown') . ". Invalid contact ID detected during creation.");
                        }
                    } catch (\Exception $e) {
                        Log::error("Error creating contact number during employee creation (Emp_ID: {$employee->Emp_ID}): " . $e->getMessage() . " Data: " . json_encode($contactData));
                        throw new \Exception("Failed to create contact record for " . ($contactData['contact_value'] ?? 'unknown') . ". Please check application logs for details.");
                    }
                }
                $employee->contactNumbers()->attach($newContactIds);
            }

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create employee: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Failed to create employee due to a server error: ' . $e->getMessage());
        }
    }

    public function terminate(Request $request, Employee $employee)
    {
        if (!$employee) {
            return redirect()->route('employees.index')->with('error', 'Employee not found.');
        }

        try {
            $employee->termination_date = Carbon::now();
            $employee->save();
            return redirect()->route('employees.index')->with('success', 'Employee Terminated Successfully');
        } catch (\Exception $e) {
            Log::error("Failed to terminate employee ID {$employee->Emp_ID}: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            return redirect()->route('employees.index')->with('error', 'Failed to terminate employee due to a server error.');
        }
    }

    public function reactivate(Request $request, Employee $employee)
    {
        if (!$employee) {
            return redirect()->route('employees.index')->with('error', 'Employee not found.');
        }

        try {
            $employee->termination_date = null;
            $employee->save();
            return redirect()->route('employees.index')->with('success', 'Employee Reactivated Successfully');
        } catch (\Exception $e) {
            Log::error("Failed to reactivate employee ID {$employee->Emp_ID}: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            return redirect()->route('employees.index')->with('error', 'Failed to reactivate employee.');
        }
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        if (!$employee->termination_date) {
            return redirect()->route('employees.index')->with('error', 'Employee must be terminated before deletion.');
        }

        try {
            $employee->contactNumbers()->detach();
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Employee Deleted Successfully');
        } catch (\Exception $e) {
            Log::error("Failed to delete employee ID {$employee->Emp_ID}: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            return redirect()->route('employees.index')->with('error', 'Failed to delete employee due to a server error.');
        }
    }

    public function edit(Employee $employee)
    {
        $employee->load('address', 'contactNumbers', 'supervisor', 'orderProcessor', 'inventoryClerk', 'shippingClerk');

        $departments = Employee::select('department')->distinct()->pluck('department')->sort()->toArray();
        $defaultDepartments = ['Warehouse', 'Inventory', 'Shipping', 'Procurement', 'Management'];
        $departments = array_unique(array_merge($defaultDepartments, $departments));
        sort($departments);

        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'F_Name' => 'required|string|max:255',
            'L_Name' => 'required|string|max:255',
            'DoB' => 'nullable|date',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state_province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'position' => 'required|string|max:255',
            'department' => 'required|string',
            'salary' => 'nullable|numeric',
            'termination_date' => 'nullable|date|after_or_equal:hire_date',
            'team_size' => 'sometimes|nullable|integer',
            'avg_processing_time' => 'sometimes|nullable|numeric',
            'accuracy_rate' => 'sometimes|nullable|numeric',
            'forklift_certification' => 'sometimes|nullable|boolean',
            'items_processed' => 'sometimes|nullable|integer',
            'error_rate' => 'sometimes|nullable|numeric',
            'packages_per_day' => 'sometimes|nullable|integer',
            'contacts' => 'nullable|array',
            'contacts.*.contact_ID' => 'nullable|integer',
            'contacts.*.contact_type' => 'required|string|in:phone,mobile,fax,email',
            'contacts.*.contact_value' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $employee) {
                    $index = explode('.', $attribute)[1];
                    $contactId = $request->input("contacts.{$index}.contact_ID");

                    $contactType = $request->input("contacts.{$index}.contact_type");

                    if ($contactType === 'email') {
                        $query = ContactNumber::where('contact_value', $value)
                            ->where('contact_type', 'email');

                        if ($contactId) {
                            $query->where('contact_ID', '!=', $contactId);
                        } else {
                            // Ambiguity fix: Explicitly qualify 'Emp_ID' with the table name 'employees'
                            $query->whereDoesntHave('employees', function ($q) use ($employee) {
                                $q->where('employees.Emp_ID', $employee->Emp_ID);
                            });
                        }

                        if ($query->exists()) {
                            $fail("The email address '{$value}' is already in use by another contact or by this employee in another entry.");
                        }
                    }
                },
            ],
            'deleted_contact_ids' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $employee->update([
                'F_Name' => $validatedData['F_Name'],
                'L_Name' => $validatedData['L_Name'],
                'DoB' => $validatedData['DoB'],
                'hire_date' => $validatedData['hire_date'],
                'position' => $validatedData['position'],
                'department' => $validatedData['department'],
                'salary' => $validatedData['salary'],
                'termination_date' => $validatedData['termination_date'] ?? null,
            ]);

            if ($employee->address) {
                $employee->address->update([
                    'street_address' => $validatedData['street_address'],
                    'city' => $validatedData['city'],
                    'state_province' => $validatedData['state_province'],
                    'postal_code' => $validatedData['postal_code'],
                    'country' => $validatedData['country'],
                ]);
            } else {
                $address = Address::create([
                    'street_address' => $validatedData['street_address'],
                    'city' => $validatedData['city'],
                    'state_province' => $validatedData['state_province'],
                    'postal_code' => $validatedData['postal_code'],
                    'country' => $validatedData['country'],
                ]);
                $employee->address()->associate($address);
                $employee->save();
            }

            $submittedContactIds = [];
            if (!empty($validatedData['deleted_contact_ids'])) {
                $idsToDeleteFromForm = array_map('intval', explode(',', $validatedData['deleted_contact_ids']));
                if (!empty($idsToDeleteFromForm)) {
                    $employee->contactNumbers()->detach($idsToDeleteFromForm);
                    ContactNumber::whereIn('contact_ID', $idsToDeleteFromForm)->delete();
                }
            }

            if (isset($validatedData['contacts']) && is_array($validatedData['contacts'])) {
                foreach ($validatedData['contacts'] as $contactData) {
                    $contact = null;
                    $contactIdFromForm = $contactData['contact_ID'] ?? null;

                    if ($contactIdFromForm) {
                        $contact = ContactNumber::find($contactIdFromForm);
                        if ($contact) {
                            $contact->update([
                                'contact_type' => $contactData['contact_type'],
                                'contact_value' => $contactData['contact_value'],
                            ]);
                            $submittedContactIds[] = (int) $contact->getKey();
                        } else {
                            Log::warning("Attempted to update non-existent contact with ID: {$contactIdFromForm}. Skipping.");
                        }
                    } else {
                        try {
                            $contact = ContactNumber::create([
                                'contact_type' => $contactData['contact_type'],
                                'contact_value' => $contactData['contact_value'],
                                'is_primary' => 0,
                            ]);
                            if ($contact && $contact->getKey() !== null) {
                                $submittedContactIds[] = (int) $contact->getKey();
                            } else {
                                Log::error("Failed to retrieve ID for newly created contact (value: " . ($contactData['contact_value'] ?? 'unknown') . ") during employee update. Contact object: " . json_encode($contact));
                                throw new \Exception("Failed to process new contact information for " . ($contactData['contact_value'] ?? 'unknown') . ".");
                            }
                        } catch (\Exception $e) {
                            Log::error("Error creating new contact number during employee update (Emp_ID: {$employee->Emp_ID}): " . $e->getMessage() . " Data: " . json_encode($contactData));
                            throw new \Exception("Failed to create new contact record for " . ($contactData['contact_value'] ?? 'unknown') . ". Please check application logs for details.");
                        }
                    }
                }
            }
            $employee->contactNumbers()->sync($submittedContactIds);

            $currentPosition = $validatedData['position'];

            if ($employee->supervisor && $currentPosition !== 'Supervisor') {
                $employee->supervisor()->delete();
            }
            if ($employee->orderProcessor && $currentPosition !== 'Order Processor') {
                $employee->orderProcessor()->delete();
            }
            if ($employee->inventoryClerk && $currentPosition !== 'Inventory Clerk') {
                $employee->inventoryClerk()->delete();
            }
            if ($employee->shippingClerk && $currentPosition !== 'Shipping Clerk') {
                $employee->shippingClerk()->delete();
            }

            switch ($currentPosition) {
                case 'Supervisor':
                    $employee->supervisor()->updateOrCreate(
                        ['Emp_ID' => $employee->Emp_ID],
                        ['team_size' => $validatedData['team_size'] ?? null]
                    );
                    break;
                case 'Order Processor':
                    $employee->orderProcessor()->updateOrCreate(
                        ['Emp_ID' => $employee->Emp_ID],
                        [
                            'Avg_time_Processing' => $validatedData['avg_processing_time'] ?? null,
                            'Accuracy_Rate' => $validatedData['accuracy_rate'] ?? null,
                        ]
                    );
                    break;
                case 'Inventory Clerk':
                    $employee->inventoryClerk()->updateOrCreate(
                        ['Emp_ID' => $employee->Emp_ID],
                        [
                            'Forklift_Certification' => $validatedData['forklift_certification'] ?? 0,
                            'Items_Processed_PerDay' => $validatedData['items_processed'] ?? null,
                        ]
                    );
                    break;
                case 'Shipping Clerk':
                    $employee->shippingClerk()->updateOrCreate(
                        ['Emp_ID' => $employee->Emp_ID],
                        [
                            'Error_Rate' => $validatedData['error_rate'] ?? null,
                            'Packages_PerDay' => $validatedData['packages_per_day'] ?? null,
                        ]
                    );
                    break;
            }

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update employee ID {$employee->Emp_ID}: " . $e->getMessage() . " Stack Trace: " . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Failed to update employee due to a server error: ' . $e->getMessage());
        }
    }
}
