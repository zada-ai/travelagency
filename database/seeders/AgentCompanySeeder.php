<?php

namespace Database\Seeders;

use App\Models\AgentCompany;
use Illuminate\Database\Seeder;

class AgentCompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Umrah Booking',
                'code' => 'UB',
                'status' => true,
            ],
            [
                'name' => 'UMRAH BOOKING (TARIQ SB)',
                'code' => 'UB-TS',
                'status' => true,
            ],
            [
                'name' => 'ARYAN AIR TRAVEL',
                'code' => 'AAT',
                'status' => true,
            ],
            [
                'name' => 'HAQ BAHOO GROUP',
                'code' => 'HBG',
                'status' => true,
            ],
        ];

        foreach ($companies as $company) {
            AgentCompany::updateOrCreate(
                ['name' => $company['name']],
                $company
            );
        }
    }
}