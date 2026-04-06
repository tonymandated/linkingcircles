<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate([
            'email' => 'admin@linkingcirclesacademy.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $superAdmin->roles()->sync(Role::whereIn('name', ['super_admin', 'student'])->get());

        // Instructor
        $instructor = User::firstOrCreate([
            'email' => 'instructor@linkingcirclesacademy.com',
        ], [
            'name' => 'Instructor User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $instructor->roles()->sync(Role::whereIn('name', ['instructor', 'student'])->get());

        // Student
        $student = User::firstOrCreate([
            'email' => 'student@linkingcirclesacademy.com',
        ], [
            'name' => 'Student User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $student->roles()->sync(Role::where('name', 'student')->first());

        // Create some additional users
        User::factory(10)->create()->each(function ($user) {
            $user->roles()->sync(Role::where('name', 'student')->first());
        });
    }
}
