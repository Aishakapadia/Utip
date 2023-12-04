<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransitTimeToLanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lanes', function (Blueprint $table) {
            $table->integer('transit_time_hrs')->default(24)->after('description');

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
            $table->dropColumn('transit_time_hrs');
        });
    }
}
