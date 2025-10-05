{{-- Termination Modal --}}
<div class="modal fade" id="terminationModal" tabindex="-1" aria-labelledby="terminationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="terminateEmployeeForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Employee Termination</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to terminate this employee? This action will mark them as inactive.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Terminate</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Reactivation Modal --}}
<div class="modal fade" id="reactivationModal" tabindex="-1" aria-labelledby="reactivationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="reactivateEmployeeForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="reactivationModalLabel">Confirm Employee Reactivation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reactivate this employee? This will mark them as active again.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Yes, Reactivate</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Delete Employee Modal --}}
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="deleteEmployeeForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to permanently delete this employee? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Employee Details Modal --}}
<div class="modal fade" id="employeeDetailsModal" tabindex="-1" aria-labelledby="employeeDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="employeeDetailsModalLabel">Employee Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Employee ID:</strong> <span id="detailEmpID"></span></p>
                        <p><strong>Name:</strong> <span id="detailFullName"></span></p>
                        <p><strong>Position:</strong> <span id="detailPosition"></span></p>
                        <p><strong>Department:</strong> <span id="detailDepartment"></span></p>
                        <p><strong>Hire Date:</strong> <span id="detailHireDate"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> <span id="detailEmail"></span></p>
                        <p><strong>Phone:</strong> <span id="detailPhone"></span></p>
                        <p><strong>Address:</strong> <span id="detailAddress"></span></p>
                        <p><strong>Supervisor:</strong> <span id="detailSupervisor"></span></p>
                        <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                    </div>
                </div>
                <hr>
                <h6>Additional Information</h6>
                <p><span id="detailAdditionalInfo" class="text-muted">No additional information available.</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
