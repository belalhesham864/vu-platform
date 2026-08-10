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
        Schema::create('interview_reschedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_slot_id')->constrained('interview_slots')->onDelete('cascade');
            $table->date('date');
            $table->time('new_start_time');
            $table->time('new_end_time');
            $table->string('reason', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_reschedules');
    }
};
