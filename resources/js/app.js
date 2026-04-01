import './bootstrap';
import Alpine from 'alpinejs';

// Import and setup jQuery FIRST, before any modules that depend on it
import $ from 'jquery';
// Make jQuery global
window.$ = window.jQuery = $;

// Now import DataTables (which depends on jQuery)
import 'datatables.net-dt';
import 'datatables.net-dt/css/jquery.dataTables.css';
// window.DataTable = DataTable;
window.Alpine = Alpine;

// Now import employee management modules (they depend on $ being available)
import './employee-datatable.js';
import './employee-filters.js';
import './employee-crud.js';
import './employee-modal.js';

Alpine.start();

console.log('Employee Management System Loaded with jQuery and DataTables');
