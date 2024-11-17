<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->references('id')->on('personnels');
            $table->foreignId('batch_id')->references('id')->on('batches');
            $table->string('departure_pressure');
            $table->string('return_pressure');
            $table->time('departure_time');
            $table->time('return_time');
            $table->string('cylinder_volume');
            $table->integer('consumption_rate')->nullable();
            $table->string('grade')->nullable();
            $table->string('status')->comment('2 is approved, 3 unapproved, 4 is pending')->default('4');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumptions');
    }
};
