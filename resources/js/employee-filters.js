// employee-filters.js - Filter and search management
import $ from 'jquery';
import 'datatables.net-dt';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

export function loadDepartments() {
    $.ajax({
        url: '/api/departments',
        type: 'GET',
        headers: {
            'Accept': 'application/json'
        },
        success: function(departments) {
            const filterSelect = $('#departmentFilter');
            const modalSelect = $('#departmentSelect');
            
            // Clear existing options (except the first one)
            filterSelect.find('option:not(:first)').remove();
            modalSelect.find('option:not(:first)').remove();
            
            departments.forEach(dept => {
                filterSelect.append(`<option value="${dept.id}">${dept.name}</option>`);
                modalSelect.append(`<option value="${dept.id}">${dept.name}</option>`);
            });
        },
        error: function(xhr) {
            console.error('Error loading departments', xhr);
        }
    });
}

export function loadManagers() {
    $.ajax({
        url: '/api/managers',
        type: 'GET',
        headers: {
            'Accept': 'application/json'
        },
        success: function(managers) {
            const filterSelect = $('#managerFilter');
            const modalSelect = $('#managerSelect');
            
            // Clear existing options (except the first one)
            filterSelect.find('option:not(:first)').remove();
            modalSelect.find('option:not(:first)').remove();
            
            managers.forEach(mgr => {
                filterSelect.append(`<option value="${mgr.id}">${mgr.full_name}</option>`);
                modalSelect.append(`<option value="${mgr.id}">${mgr.full_name}</option>`);
            });
        },
        error: function(xhr) {
            console.error('Error loading managers', xhr);
        }
    });
}

export function applyFilters() {
    // Use DataTables API to reload with new filters
    import('./employee-datatable.js').then(module => {
        module.reloadDataTable();
    });
}

// Make functions globally accessible
window.applyFilters = applyFilters;

flatpickr('#joiningDateRange', {
    mode: 'range',
    dateFormat: 'Y-m-d',
    onClose: function () {
        applyFilters();
    }
});

// Initialize on document ready
$(document).ready(function() {
    loadDepartments();
    loadManagers();
    
    // Initialize DataTable
    import('./employee-datatable.js').then(module => {
        module.initializeDataTable();
    });
});

