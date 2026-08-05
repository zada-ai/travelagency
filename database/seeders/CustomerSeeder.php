<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('Customer');

        Customer::updateOrCreate(
            ['user_id' => $user->id],
            [
                'customer_code' => 'CUST-' . strtoupper(Str::random(6)),
                'first_name' => 'Customer',
                'last_name' => 'User',
                'phone' => '+923000000000',
                'cnic' => '35202-1234567-1',
                'passport_no' => 'PA' . strtoupper(Str::random(8)),
                'passport_expiry' => now()->addYear(),
                'nationality' => 'Pakistani',
                'date_of_birth' => now()->subYears(28)->toDateString(),
                'address' => 'Customer Address',
                'city' => 'Lahore',
                'country' => 'Pakistan',
                'status' => 'active',
            ]
        );
    }
}
