<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('permissions');

        $allPermissions = [];
        foreach ($permissions as $group) {
            $allPermissions = array_merge($allPermissions, array_keys($group));
        }

        // Super Admin
        Role::firstOrCreate([
            'name' => 'super_admin',
        ], [
            'label' => 'Super Admin',
            'permissions' => $allPermissions,
            'is_system' => true,
        ]);

        // Instructor
        Role::firstOrCreate([
            'name' => 'instructor',
        ], [
            'label' => 'Instructor',
            'permissions' => [
                'courses.view',
                'courses.create',
                'courses.update',
                'courses.publish',
                'lessons.view',
                'lessons.create',
                'lessons.update',
                'lessons.publish',
                'enrollments.view',
                'users.view',
            ],
            'is_system' => true,
        ]);

        // Student
        Role::firstOrCreate([
            'name' => 'student',
        ], [
            'label' => 'Student',
            'permissions' => [
                'courses.view',
                'lessons.view',
                'enrollments.view',
                'enrollments.create',
            ],
            'is_system' => true,
        ]);
    }
}
