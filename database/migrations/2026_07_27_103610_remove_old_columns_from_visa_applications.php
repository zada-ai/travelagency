<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('visa_applications', function (Blueprint $table) {

        $table->dropColumn([
            'customer_name',
            'passport_number',
            'passport_expiry',
            'nationality',
            'travel_date',
            'return_date',
            'passport_copy',
            'cnic_copy',
            'photograph',
            'vaccination_certificate',
            'visa_copy',
        ]);

    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visa_applications', function (Blueprint $table) {
            //
        });
    }
};
