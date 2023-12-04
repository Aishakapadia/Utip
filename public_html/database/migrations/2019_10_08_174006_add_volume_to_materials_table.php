<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVolumeToMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            //$table->float('volume')->default(0)->after('description');
            DB::statement('ALTER TABLE `materials` ADD `volume` FLOAT NOT NULL  DEFAULT 0 AFTER `description`');
        });

        Schema::table('ticket_details', function (Blueprint $table) {
            //$table->float('volume')->default(0);
            DB::statement('ALTER TABLE `ticket_details` ADD `volume` FLOAT  DEFAULT 0 NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('volume');
        });

        Schema::table('ticket_details', function (Blueprint $table) {
            $table->dropColumn('volume');
        });
    }
}
