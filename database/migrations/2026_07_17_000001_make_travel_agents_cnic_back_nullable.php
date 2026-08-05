<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('travel_agents')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE `travel_agents` MODIFY `cnic_back` VARCHAR(255) NULL');
    }

    public function down()
    {
        if (! Schema::hasTable('travel_agents')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE `travel_agents` MODIFY `cnic_back` VARCHAR(255) NOT NULL');
    }
};
