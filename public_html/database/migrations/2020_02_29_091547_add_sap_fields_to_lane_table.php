<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSapFieldsToLaneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lanes', function (Blueprint $table) {            
            $table->string('plant_code')->unique()->nullable();
            $table->string('shipment_type')->unique()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lanes', function (Blueprint $table) {
            $table->dropColumn('plant_code');
            $table->dropColumn('shipment_type');
        });
    }
}
