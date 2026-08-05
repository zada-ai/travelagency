<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

it('redirects a customer to the dedicated customer dashboard route', function () {
    $role = Role::findOrCreate('Customer');

    $customer = User::factory()->create([
        'name' => 'Ali Customer',
        'email' => 'customer@example.com',
        'password' => bcrypt('password'),
    ]);

    $customer->assignRole($role);

    $response = $this->actingAs($customer)->get('/customer/dashboard');

    $response->assertOk();
    $response->assertSee('Customer Portal');
});
