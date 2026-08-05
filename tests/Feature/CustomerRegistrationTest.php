<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('creates a customer account and profile from the registration form', function () {
    Role::firstOrCreate(['name' => 'Customer']);

    $response = $this->post('/register', [
        'name' => 'Ahmed Raza',
        'email' => 'ahmed@example.com',
        'mobile_number' => '+92 300 1234567',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'whatsapp_number' => '+92 333 7654321',
        'nationality' => 'Pakistan',
        'cnic' => '42201-1234567-8',
        'passport_number' => 'PK1234567',
        'passport_expiry' => '2035-01-01',
        'country' => 'Pakistan',
        'city' => 'Karachi',
        'address' => 'House 1, Street 2',
        'emergency_contact_name' => 'Ali Raza',
        'relationship' => 'Brother',
        'emergency_contact_number' => '+92 321 1111111',
        'terms' => '1',
        'privacy_policy' => '1',
    ]);

    $response->assertRedirectToRoute('customer.dashboard');
    $this->assertAuthenticated();

    $user = User::where('email', 'ahmed@example.com')->firstOrFail();

    expect(Hash::check('password123', $user->password))->toBeTrue()
        ->and($user->hasRole('Customer'))->toBeTrue();

    $customer = Customer::where('user_id', $user->id)->firstOrFail();

    expect($customer->first_name)->toBe('Ahmed')
        ->and($customer->last_name)->toBe('Raza')
        ->and($customer->phone)->toBe('+92 300 1234567')
        ->and($customer->cnic)->toBe('42201-1234567-8')
        ->and($customer->passport_no)->toBe('PK1234567')
        ->and($customer->nationality)->toBe('Pakistan')
        ->and($customer->city)->toBe('Karachi')
        ->and($customer->country)->toBe('Pakistan');
});
