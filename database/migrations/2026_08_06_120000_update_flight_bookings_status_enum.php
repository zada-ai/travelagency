<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend allowed status values to match application uses
        DB::statement("ALTER TABLE `flight_bookings` MODIFY `status` ENUM('Pending','Reserved','Confirmed','Approved','Cancelled','Rejected') NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        // Revert to original minimal set
        DB::statement("ALTER TABLE `flight_bookings` MODIFY `status` ENUM('Pending','Confirmed','Cancelled') NOT NULL DEFAULT 'Pending'");
    }
};
