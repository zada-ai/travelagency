<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Saare roles ki list aur unhe create karna
        $roles = [
            'Super Admin',
            'Admin',
            'Sales Executive',
            'Visa Officer',
            'Accounts Officer',
            'Transport Officer',
            'Hotel Manager',
            'Agent (B2B)',
            'Customer'
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        // 2. Default Super Admin User banana
        $admin = User::updateOrCreate(
            ['email' => 'umrah@agency.pk'], // Agar is email se user pehle se hai to update karega, nahi to naya banayega
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // User ko Super Admin ka role dena
        $admin->assignRole('Super Admin');
    }
}