// employee-crud.js - CRUD operations (Create, Read, Update, Delete)
import { setCurrentPage, getCurrentPage } from './employee-modal.js';

export function deleteEmployee(id) {
    if (confirm('Are you sure you want to delete this employee?')) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: `/employees/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Accept': 'application/json'
            },
            success: function(data) {
                if (data.success) {
                    alert(data.message);
                    // Reload DataTable
                    import('./employee-datatable.js').then(module => {
                        module.reloadDataTable();
                    });
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred while deleting the employee');
                console.error(xhr);
            }
        });
    }
}

export function loadEmployees(page = 1) {
    const params = {
        page: page,
        search: $('#searchInput').val(),
        department_id: $('#departmentFilter').val(),
        manager_id: $('#managerFilter').val(),
        joining_date_from: $('#joinDateFrom').val(),
        joining_date_to: $('#joinDateTo').val(),
        per_page: 10
    };

    $.ajax({
        url: '/employees',
        type: 'GET',
        headers: {
            'Accept': 'application/json'
        },
        data: params,
        success: function(data) {
            renderTable(data.data);
            renderPagination(data);
            setCurrentPage(data.current_page);
        },
        error: function(xhr) {
            alert('Error loading employees');
            console.error(xhr);
        }
    });
}

function renderTable(employees) {
    const tbody = $('#tableBody');
    tbody.empty();

    if (employees.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">No employees found</td></tr>');
        return;
    }

    employees.forEach(emp => {
        const deptName = emp.department?.name || '-';
        const mgrName = emp.manager?.full_name || '-';
        const joinDate = formatDate(emp.joining_date);

        const row = `
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 text-sm text-blue-600 dark:text-blue-400 cursor-pointer font-medium" onclick="editEmployee(${emp.id})">
                    ${emp.full_name}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${emp.employee_code}</td>
                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${deptName}</td>
                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${mgrName}</td>
                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${joinDate}</td>
                <td class="px-6 py-4 text-sm text-center">
                    <button onclick="editEmployee(${emp.id})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3">
                        ✎ Edit
                    </button>
                    <button onclick="deleteEmployee(${emp.id})" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                        🗑 Delete
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function renderPagination(data) {
    const pagination = $('#pagination');
    pagination.empty();

    if (data.last_page <= 1) return;

    // Previous button
    const prevDisabled = data.current_page === 1;
    const prevBtn = $(`<button class="px-4 py-2 rounded ${prevDisabled ? 'bg-gray-200 dark:bg-gray-700 text-gray-400' : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-700'}"${prevDisabled ? ' disabled' : ''}>Previous</button>`);
    prevBtn.on('click', () => !prevDisabled && loadEmployees(data.current_page - 1));
    pagination.append(prevBtn);

    // Page buttons
    for (let i = 1; i <= data.last_page; i++) {
        const pageBtn = $(`<button class="px-3 py-2 rounded ${i === data.current_page ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-700'}">${i}</button>`);
        pageBtn.on('click', () => loadEmployees(i));
        pagination.append(pageBtn);
    }

    // Next button
    const nextDisabled = data.current_page === data.last_page;
    const nextBtn = $(`<button class="px-4 py-2 rounded ${nextDisabled ? 'bg-gray-200 dark:bg-gray-700 text-gray-400' : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-700'}"${nextDisabled ? ' disabled' : ''}>Next</button>`);
    nextBtn.on('click', () => !nextDisabled && loadEmployees(data.current_page + 1));
    pagination.append(nextBtn);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });
}

// Make functions globally accessible
window.deleteEmployee = deleteEmployee;
window.loadEmployees = loadEmployees;