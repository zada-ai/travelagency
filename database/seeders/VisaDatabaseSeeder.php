<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisaType;
use App\Models\VisaApplication;
use App\Models\TravelAgent;
use App\Models\User;

class VisaDatabaseSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'name' => 'Umrah Tourist Visa (eVisa)',
                'code' => 'UMR-TOURIST',
                'description' => 'Standard eVisa for tourist entry and Umrah pilgrims.',
                'base_fee' => 350.00,
                'service_charge' => 50.00,
                'is_active' => true,
            ],
            [
                'name' => 'Umrah Group Visa',
                'code' => 'UMR-GROUP',
                'description' => 'Group entry visa processed under dynamic group approvals.',
                'base_fee' => 300.00,
                'service_charge' => 40.00,
                'is_active' => true,
            ],
            [
                'name' => 'Individual Pilgrim Visa',
                'code' => 'UMR-INDIVIDUAL',
                'description' => 'Dedicated pilgrimage visa with direct ministry logging.',
                'base_fee' => 450.00,
                'service_charge' => 60.00,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            VisaType::updateOrCreate(['code' => $type['code']], $type);
        }

        $agent = TravelAgent::first();
        $officer = User::first();
        $type = VisaType::first();

        if ($type) {
            VisaApplication::create([
                'customer_name' => 'Zeeshan Ahmad',
                'passport_number' => 'PB1234567',
                'passport_expiry' => now()->addYears(5)->format('Y-m-d'),
                'nationality' => 'Pakistani',
                'travel_date' => now()->addDays(15)->format('Y-m-d'),
                'return_date' => now()->addDays(30)->format('Y-m-d'),
                'visa_type_id' => $type->id,
                'travel_agent_id' => $agent?->id,
                'visa_officer_id' => $officer?->id,
                'visa_fee' => $type->base_fee,
                'service_charges' => $type->service_charge,
                'total_amount' => $type->base_fee + $type->service_charge,
                'status' => 'Pending',
                'remarks' => 'Urgent processing requested.',
            ]);

            VisaApplication::create([
                'customer_name' => 'Fatima Bibi',
                'passport_number' => 'PB7654321',
                'passport_expiry' => now()->addYears(3)->format('Y-m-d'),
                'nationality' => 'Pakistani',
                'travel_date' => now()->addDays(20)->format('Y-m-d'),
                'return_date' => now()->addDays(35)->format('Y-m-d'),
                'visa_type_id' => $type->id,
                'travel_agent_id' => $agent?->id,
                'visa_officer_id' => null,
                'visa_fee' => $type->base_fee,
                'service_charges' => $type->service_charge,
                'total_amount' => $type->base_fee + $type->service_charge,
                'status' => 'Draft',
                'remarks' => 'Documents compilation in progress.',
            ]);
        }
    }
}
