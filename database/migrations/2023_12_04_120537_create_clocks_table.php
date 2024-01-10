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
        Schema::create('clocks', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->date('date');
            $table->time('inclock_time')->nullable();
            $table->time('outclock_time')->nullable();
            $table->string('excercise');
            $table->enum('status', ['aanwezig', 'afwezig', 'ziek', 'te_laat', 'vrij']);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clocks');
    }
};
