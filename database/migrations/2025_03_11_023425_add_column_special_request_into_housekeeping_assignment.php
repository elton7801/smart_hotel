<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('housekeeping_assignments', function (Blueprint $table) {
            $table->string('special_request')->nullable()->after('time_slot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('housekeeping_assignments', function (Blueprint $table) {
            $table->dropColumn('special_request');
        });
    }
};
