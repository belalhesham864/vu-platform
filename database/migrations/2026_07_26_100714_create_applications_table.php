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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->enum('application_type', ['AI Interview', 'Technical Interview' , 'Final Interview'])->default('AI Interview');
            $table->enum('status', ['Under Review', 'Scheduled','Shortlisted' , 'Accepted', 'Rejected'])->default('Under Review');
            $table->string('decision')->nullable();
            $table->date('decision_date')->nullable();
            $table->date('start_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
