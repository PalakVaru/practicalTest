<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold">Employees List</h1>
                        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md">
                            + Add Employee
                        </button>
                    </div>

                    <!-- Filters Section -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Department Filter -->
                            <select 
                                id="departmentFilter" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="applyFilters()"
                            >
                                <option value="">All Departments</option>
                            </select>

                            <!-- Manager Filter -->
                            <select 
                                id="managerFilter" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="applyFilters()"
                            >
                                <option value="">All Managers</option>
                            </select>

                            <!-- From Date -->
                            <input
                                type="text"
                                id="joiningDateRange"
                                placeholder="Select joining date range"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="applyFilters()"
                            >
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table id="employeeTable" class="min-w-full border-collapse">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Employee Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Employee Code</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Department</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Manager</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Joined Date</th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="employeeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalTitle" class="text-2xl font-bold dark:text-gray-100">Add Employee</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="employeeForm" onsubmit="saveEmployee(event)">
                @csrf
                <input type="hidden" id="employeeId" value="">

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Full Name</label>
                    <input 
                        type="text" 
                        id="fullName" 
                        name="full_name" 
                        placeholder="Enter Full Name"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-100" 
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Employee Code</label>
                    <input 
                        type="text" 
                        id="employeeCode" 
                        name="employee_code" 
                        placeholder="Enter Employee Code"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-100" 
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Department</label>
                    <select 
                        id="departmentSelect" 
                        name="department_id" 
                        placeholder="Select Department"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-100"
                    >
                        <option value="">Select Department</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Manager</label>
                    <select 
                        id="managerSelect" 
                        name="manager_id" 
                        placeholder="Select Manager"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-100"
                    >
                        <option value="">Select Manager</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Joining Date</label>
                    <input 
                        type="date" 
                        id="joiningDate" 
                        name="joining_date" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-100" 
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter Email Address"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-100" 
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Phone Number</label>
                    <input 
                        type="tel" 
                        id="phoneNumber" 
                        name="phone_number" 
                        placeholder="Enter Phone Number"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-100"
                    >
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Address</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        placeholder="Enter Address"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-gray-100 resize-none" 
                        rows="3"
                    ></textarea>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeModal()" 
                        class="flex-1 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 font-bold py-2 px-4 rounded"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Save Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
