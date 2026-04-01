<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create departments
        $departments = [
            ['name' => 'Sales', 'description' => 'Sales Department'],
            ['name' => 'HR', 'description' => 'Human Resources Department'],
            ['name' => 'IT', 'description' => 'Information Technology Department'],
            ['name' => 'Marketing', 'description' => 'Marketing Department'],
            ['name' => 'Finance', 'description' => 'Finance Department'],
            ['name' => 'Operations', 'description' => 'Operations Department'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }

        // Create sample employees
        $employees = [
            [
                'employee_code' => 'E001',
                'full_name' => 'John Smith',
                'department_id' => 1,
                'manager_id' => null,
                'joining_date' => '2020-02-15',
                'email' => 'john.smith@example.com',
                'phone_number' => '1234567890',
                'address' => '123 Main St, City, State'
            ],
            [
                'employee_code' => 'E002',
                'full_name' => 'Emma Johnson',
                'department_id' => 2,
                'manager_id' => null,
                'joining_date' => '2019-10-05',
                'email' => 'emma.johnson@example.com',
                'phone_number' => '0987654321',
                'address' => '456 Oak Ave, City, State'
            ],
            [
                'employee_code' => 'E003',
                'full_name' => 'Michael Brown',
                'department_id' => 3,
                'manager_id' => null,
                'joining_date' => '2021-08-22',
                'email' => 'michael.brown@example.com',
                'phone_number' => '5555555555',
                'address' => '789 Elm St, City, State'
            ],
            [
                'employee_code' => 'E004',
                'full_name' => 'Sophia Davis',
                'department_id' => 4,
                'manager_id' => 1,
                'joining_date' => '2018-11-05',
                'email' => 'sophia.davis@example.com',
                'phone_number' => '6666666666',
                'address' => '321 Pine Rd, City, State'
            ],
            [
                'employee_code' => 'E005',
                'full_name' => 'Daniel Wilson',
                'department_id' => 5,
                'manager_id' => null,
                'joining_date' => '2022-03-18',
                'email' => 'daniel.wilson@example.com',
                'phone_number' => '7777777777',
                'address' => '654 Maple Dr, City, State'
            ],
            [
                'employee_code' => 'E006',
                'full_name' => 'Olivia Martinez',
                'department_id' => 6,
                'manager_id' => null,
                'joining_date' => '2017-06-24',
                'email' => 'olivia.martinez@example.com',
                'phone_number' => '8888888888',
                'address' => '987 Birch Ln, City, State'
            ],
            [
                'employee_code' => 'E007',
                'full_name' => 'James Taylor',
                'department_id' => 1,
                'manager_id' => 1,
                'joining_date' => '2020-09-30',
                'email' => 'james.taylor@example.com',
                'phone_number' => '9999999999',
                'address' => '147 Cedar Ave, City, State'
            ],
            [
                'employee_code' => 'E008',
                'full_name' => 'Ava Thompson',
                'department_id' => 2,
                'manager_id' => 2,
                'joining_date' => '2019-12-12',
                'email' => 'ava.thompson@example.com',
                'phone_number' => '1010101010',
                'address' => '258 Spruce Way, City, State'
            ],
        ];

        foreach ($employees as $emp) {
            Employee::firstOrCreate(['email' => $emp['email']], $emp);
        }
    }
}

