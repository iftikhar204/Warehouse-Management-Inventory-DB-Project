@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="container-fluid px-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4 animate__animated animate__fadeInDown">
        <h1 class="h3 fw-bold text-primary d-flex align-items-center">
            <i class="bi bi-people-fill me-2"></i> Employee Management
        </h1>
        <a href="{{ route('employees.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Employee
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm animate__animated animate__fadeInUp" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm animate__animated animate__fadeInUp" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Employee Summary Stats -->
    <div class="row g-4 mb-4">
        @php
            $cards = [
                ['label' => 'Total Employees', 'count' => $employees->count(), 'icon' => 'people', 'color' => 'text-primary'],
                ['label' => 'Order Processors', 'count' => $roleCounts['Order Processor'] ?? 0, 'icon' => 'cart-check', 'color' => 'text-success'],
                ['label' => 'Inventory Clerks', 'count' => $roleCounts['Inventory Clerk'] ?? 0, 'icon' => 'clipboard-data', 'color' => 'text-info'],
                ['label' => 'Shipping Clerks', 'count' => $roleCounts['Shipping Clerk'] ?? 0, 'icon' => 'truck', 'color' => 'text-warning'],
                ['label' => 'Supervisors', 'count' => $roleCounts['Supervisor'] ?? 0, 'icon' => 'person-badge', 'color' => 'text-danger'],
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-6 col-md-4 col-lg-3 col-xl-2 animate__animated animate__fadeInUp">
            <div class="border rounded shadow-sm bg-white p-3 h-100 card-hover-effect d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-{{ $card['icon'] }} fs-3 me-2 {{ $card['color'] }}"></i>
                    <div>
                        <div class="fw-semibold small text-muted">{{ $card['label'] }}</div>
                        <div class="fs-4 fw-bold">{{ $card['count'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Employee List -->
    <div class="list-group shadow-sm rounded overflow-hidden mb-4">
        @forelse ($employees as $employee)
        <div class="list-group-item list-group-item-action py-3 px-4 d-flex justify-content-between align-items-start flex-wrap flex-md-nowrap employee-hover animate__animated animate__fadeInUp">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width: 45px; height: 45px;">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
                <div>
                    <div class="fw-bold">{{ $employee->F_Name }} {{ $employee->L_Name }}</div>
                    <div class="text-muted small">{{ $employee->position }} &mdash; {{ $employee->department }}</div>
                    <div class="small">
                        <i class="bi bi-calendar me-1"></i> Hired: {{ optional($employee->hire_date)->format('Y-m-d') }} |
                        <i class="bi bi-person-badge me-1"></i> Supervisor:
                        @php
                            $supervisorEmployee = $employee->mySupervisor->first()->employee ?? null;
                        @endphp
                        {{ $supervisorEmployee ? $supervisorEmployee->F_Name . ' ' . $supervisorEmployee->L_Name : 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mt-3 mt-md-0">
                <span class="badge {{ $employee->termination_date ? 'bg-secondary' : 'bg-success' }}">
                    {{ $employee->termination_date ? 'Terminated' : 'Active' }}
                </span>

                <a href="{{ route('employees.edit', $employee->Emp_ID) }}" class="btn btn-sm btn-outline-primary shadow-sm" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <button type="button" class="btn btn-sm btn-outline-secondary view-employee-details shadow-sm" data-id="{{ $employee->Emp_ID }}" title="View Details">
                    <i class="bi bi-eye"></i>
                </button>

                @if ($employee->termination_date)
                    <button type="button" class="btn btn-sm btn-outline-info shadow-sm reactivate-employee" data-id="{{ $employee->Emp_ID }}" title="Reactivate">
                        <i class="bi bi-person-check"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger shadow-sm delete-employee" data-id="{{ $employee->Emp_ID }}" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-sm btn-outline-dark shadow-sm terminate-employee" data-id="{{ $employee->Emp_ID }}" title="Terminate">
                        <i class="bi bi-person-x"></i>
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="list-group-item text-center text-muted py-5 animate__animated animate__fadeIn">
            <i class="bi bi-info-circle me-1"></i> No employees found.
        </div>
        @endforelse
    </div>

    <!-- Include All Modals -->
    @include('employees.partials.modals')

</div>
@endsection

@section('styles')
<style>
    .card-hover-effect {
        transition: all 0.3s ease;
    }

    .card-hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .employee-hover {
        transition: background-color 0.3s ease;
    }

    .employee-hover:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('scripts')
<script>
    $(function () {
        // Modal logic
        const handleModal = (selector, route, formId, modalId, method = 'PUT') => {
            $(selector).click(function () {
                const id = $(this).data('id');
                $(`#${formId}`).attr('action', `/employees/${id}/${route}`);
                $(`#${modalId}`).modal('show');
            });
        };

        handleModal('.terminate-employee', 'terminate', 'terminateEmployeeForm', 'terminationModal');
        handleModal('.reactivate-employee', 'reactivate', 'reactivateEmployeeForm', 'reactivationModal');
        handleModal('.delete-employee', '', 'deleteEmployeeForm', 'deleteEmployeeModal');

        $('.view-employee-details').click(function () {
            const empId = $(this).data('id');
            $.get(`/employees/${empId}/details`, function (employee) {
                $('#detailEmpID').text(employee.Emp_ID);
                $('#detailFullName').text(`${employee.F_Name} ${employee.L_Name}`);
                $('#detailPosition').text(employee.position);
                $('#detailDepartment').text(employee.department);
                $('#detailHireDate').text(employee.hire_date ? new Date(employee.hire_date).toLocaleDateString() : 'N/A');
                $('#detailEmail').text(employee.email || 'N/A');
                $('#detailPhone').text(employee.phone || 'N/A');
                $('#detailAddress').text(employee.address ? `${employee.address.street_address}, ${employee.address.city}, ${employee.address.country}` : 'N/A');
                $('#detailSupervisor').text(employee.supervisor_name || 'N/A');
                $('#detailStatus').html(employee.termination_date ? '<span class="badge bg-secondary">Terminated</span>' : '<span class="badge bg-success">Active</span>');
                $('#detailAdditionalInfo').text(employee.notes || 'No additional information available.');
                $('#employeeDetailsModal').modal('show');
            }).fail(function () {
                alert("Failed to load employee details.");
            });
        });
    });
</script>
@endsection
