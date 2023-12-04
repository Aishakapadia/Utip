<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id');
            $table->integer('status_id');
            $table->string('transporter')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_nic')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('status')->nullable();
            $table->string('site_from');
            $table->string('site_to');
            $table->string('type')->nullable();
            $table->string('inspection_site')->nullable();
            $table->longText('comments')->nullable();
            $table->json('questions');
            $table->json('responses');
            $table->boolean('selected')->default(1);
            $table->string('submitted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklists');
    }
}
