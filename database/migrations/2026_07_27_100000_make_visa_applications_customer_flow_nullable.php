<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        DB::statement(<<<'SQL'
ALTER TABLE `visa_applications`
    MODIFY `customer_name` varchar(255) NULL,
    MODIFY `passport_number` varchar(255) NULL,
    MODIFY `passport_expiry` date NULL,
    MODIFY `nationality` varchar(255) NULL,
    MODIFY `travel_date` date NULL,
    MODIFY `visa_type_id` bigint unsigned NULL,
    MODIFY `visa_fee` decimal(10,2) NULL,
    MODIFY `service_charges` decimal(10,2) NULL,
    MODIFY `total_amount` decimal(10,2) NULL;
SQL
        );
    }

    public function down()
    {
        DB::statement(<<<'SQL'
ALTER TABLE `visa_applications`
    MODIFY `customer_name` varchar(255) NOT NULL,
    MODIFY `passport_number` varchar(255) NOT NULL,
    MODIFY `passport_expiry` date NOT NULL,
    MODIFY `nationality` varchar(255) NOT NULL,
    MODIFY `travel_date` date NOT NULL,
    MODIFY `visa_type_id` bigint unsigned NOT NULL,
    MODIFY `visa_fee` decimal(10,2) NOT NULL,
    MODIFY `service_charges` decimal(10,2) NOT NULL,
    MODIFY `total_amount` decimal(10,2) NOT NULL;
SQL
        );
    }
};
