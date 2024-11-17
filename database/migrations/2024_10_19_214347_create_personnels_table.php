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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->references('id')->on('batches');
            $table->foreignId('country_id')->references('id')->on('countries');
            $table->string('name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('id_number')->nullable();
            $table->string('email')->nullable();            
            $table->string('rank')->nullable();
            $table->string('age');
            $table->string('airport');
            $table->string('status')->nullable()->comment('2 is active , 3 is graduated')->default('2');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
