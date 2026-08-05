<?php

use App\Models\Customer;
use App\Models\User;
use App\Models\VisaApplication;
use App\Models\VisaApplicant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('stores a visa application with applicant records for a customer', function () {
    Storage::fake('public');

    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);
    $customer = Customer::create([
        'user_id' => $user->id,
        'customer_code' => 'CUST-001',
        'first_name' => 'Test',
        'last_name' => 'Customer',
        'phone' => '03000000000',
        'status' => 'active',
    ]);

    $this->actingAs($user);

    $response = $this->post(route('customer.visa.store'), [
        'total_persons' => 2,
        'adults' => 2,
        'children' => 0,
        'infants' => 0,
        'visa_type' => 'Umrah',
        'applicants' => [
            [
                'full_name' => 'Ali Khan',
                'father_name' => 'Khan Baba',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'nationality' => 'Pakistan',
                'passport_number' => 'PA12345',
                'passport_expiry_date' => '2030-01-01',
                'mobile_number' => '03001234567',
                'email' => 'ali@example.com',
                'address' => 'Street 1',
                'passport_scan' => UploadedFile::fake()->image('passport.jpg'),
                'photo' => UploadedFile::fake()->image('photo.jpg'),
                'cnic' => UploadedFile::fake()->image('cnic.jpg'),
            ],
            [
                'full_name' => 'Sara Khan',
                'father_name' => 'Khan Baba',
                'gender' => 'female',
                'date_of_birth' => '1995-01-01',
                'nationality' => 'Pakistan',
                'passport_number' => 'PA54321',
                'passport_expiry_date' => '2031-01-01',
                'mobile_number' => '03007654321',
                'email' => 'sara@example.com',
                'address' => 'Street 2',
                'passport_scan' => UploadedFile::fake()->image('passport2.jpg'),
                'photo' => UploadedFile::fake()->image('photo2.jpg'),
                'cnic' => UploadedFile::fake()->image('cnic2.jpg'),
            ],
        ],
    ]);

    $response->assertRedirect(route('customer.visa.index'));

    $application = VisaApplication::where('customer_id', $customer->id)->latest()->first();
    expect($application)->not->toBeNull();
    expect($application->applicants()->count())->toBe(2);
    expect(VisaApplicant::where('visa_application_id', $application->id)->count())->toBe(2);
});
