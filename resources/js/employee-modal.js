// employee-modal.js - Modal management functions
import $ from 'jquery';
import 'datatables.net-dt';

let currentPage = 1;

export function openAddModal() {
    $('#modalTitle').text('Add Employee');
    $('#employeeForm').trigger('reset');
    $('#employeeId').val('');
    $('#employeeModal').removeClass('hidden');
}

export function closeModal() {
    $('#employeeModal').addClass('hidden');
    $('#employeeForm').trigger('reset');
}

export function editEmployee(id) {
    $.ajax({
        url: `/employees/${id}/edit`,
        type: 'GET',
        headers: {
            'Accept': 'application/json'
        },
        success: function(data) {
            $('#modalTitle').text('Edit Employee');
            $('#employeeId').val(data.employee.id);
            $('#fullName').val(data.employee.full_name);
            $('#employeeCode').val(data.employee.employee_code);
            $('#departmentSelect').val(data.employee.department_id || '');
            $('#managerSelect').val(data.employee.manager_id || '');
            $('#joiningDate').val(data.employee.joining_date);
            $('#email').val(data.employee.email);
            $('#phoneNumber').val(data.employee.phone_number || '');
            $('#address').val(data.employee.address || '');
            
            $('#employeeModal').removeClass('hidden');
        },
        error: function(xhr) {
            alert('Error loading employee data');
            console.error(xhr);
        }
    });
}

export function saveEmployee(event) {
    event.preventDefault();

    const employeeId = $('#employeeId').val();
    const formData = new FormData($('#employeeForm')[0]);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    const url = employeeId ? `/employees/${employeeId}` : '/employees';
    const method = employeeId ? 'POST' : 'POST';  // Laravel requires POST with _method field
    
    // For PUT requests, add _method field since FormData doesn't support PUT directly
    if (employeeId) {
        formData.append('_method', 'PUT');
    }

    $.ajax({
        url: url,
        type: method,
        headers: {
            'X-CSRF-Token': csrfToken,
            'Accept': 'application/json'
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.success) {
                alert(data.message);
                closeModal();
                // Reload DataTable
                import('./employee-datatable.js').then(module => {
                    module.reloadDataTable();
                });
            } else {
                alert('Error: ' + data.message);
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                // Show detailed validation errors
                const errors = xhr.responseJSON.errors || {};
                let errorMessage = 'Validation Error:\n';
                
                // Build error message from all validation errors
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        errorMessage += `\n${field}: ${errors[field].join(', ')}`;
                    }
                }
                
                alert(errorMessage.trim() || 'Validation error');
            } else {
                alert('An error occurred while saving the employee');
            }
            console.error(xhr);
        }
    });
}

export function setCurrentPage(page) {
    currentPage = page;
}

export function getCurrentPage() {
    return currentPage;
}

// Make functions globally accessible
window.openAddModal = openAddModal;
window.closeModal = closeModal;
window.editEmployee = editEmployee;
window.saveEmployee = saveEmployee;
