<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create(config('db-scheduler.table_name'), function (Blueprint $table) {
            $table->id();
            $table->string('command');
            $table->string('cron_expression');
            $table->string('arguments')->nullable();
            $table->string('options')->nullable();
            $table->string('environments')->nullable();
            $table->tinyInteger('status')->comment("0 - Disabled, 1 - Enabled");
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
        Schema::dropIfExists(config('db-scheduler.table_name'));
    }
};
