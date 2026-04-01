// employee-datatable.js - DataTables initialization and management
import $ from 'jquery';
import 'datatables.net-dt';

let datatable = null;

export function initializeDataTable() {
    // Initialize DataTables with AJAX
    datatable = $('#employeeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/employees',
            type: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            data: function(d) {
                const dateRange = $('#joiningDateRange').val();
                // Add custom filter parameters that DataTables will send
                d.department_id = $('#departmentFilter').val();
                d.manager_id = $('#managerFilter').val();
                const dates = dateRange.split(' to ');
                d.joining_date_from = dates[0] || '';
                d.joining_date_to = dates[1] || '';
                return d;
            }
        },
        columns: [
            { 
                data: 'full_name',
                render: function(data, type, row) {
                    return '<span class="cursor-pointer text-blue-600 font-medium hover:underline" onclick="editEmployee(' + row.id + ')">' + data + '</span>';
                }
            },
            { data: 'employee_code' },
            { 
                data: 'department',
                render: function(data) {
                    return data?.name || '-';
                }
            },
            { 
                data: 'manager',
                render: function(data) {
                    return data?.full_name || '-';
                }
            },
            { 
                data: 'joining_date',
                render: function(data) {
                    return formatDate(data);
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="flex gap-2 justify-center">
                            <button onclick="editEmployee(${row.id})" class="px-3 py-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded">
                                Edit
                            </button>
                            <button onclick="deleteEmployee(${row.id})" class="px-3 py-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded">
                                Delete
                            </button>
                        </div>
                    `;
                },
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            search: 'Search:',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            paginate: {
                previous: 'Previous',
                next: 'Next',
                first: 'First',
                last: 'Last'
            },
            infoEmpty: 'No entries to show',
            processing: 'Processing...',
            zeroRecords: 'No matching records found'
        },
        dom: '<"top"lf>rt<"bottom"ip><"clear">',
        drawCallback: function() {
            // Apply dark mode styles if needed
            if (document.documentElement.classList.contains('dark')) {
                $('#employeeTable').addClass('dark');
            }
        }
    });

    return datatable;
}

export function reloadDataTable() {
    if (datatable) {
        datatable.ajax.reload();
    }
}

export function getDataTable() {
    return datatable;
}

// Make functions globally accessible
window.initializeDataTable = initializeDataTable;
window.reloadDataTable = reloadDataTable;
window.getDataTable = getDataTable;

// Format date utility (MM/DD/YYYY format)
export function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: '2-digit', 
        day: '2-digit' 
    });
}

window.formatDate = formatDate;
