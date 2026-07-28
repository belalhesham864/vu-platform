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

            $table->foreignId('interview_slot_id')
                ->constrained()
                ->cascadeOnDelete();

            
            $table->foreignId('requested_by')
                ->constrained('candidates')
                ->cascadeOnDelete();

            $table->date('date');
            
            $table->dateTime('old_start_time');

            $table->dateTime('old_end_time');


            $table->time('new_start_time');

            $table->time('new_end_time');


            $table->text('reason')->nullable();


            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');
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
