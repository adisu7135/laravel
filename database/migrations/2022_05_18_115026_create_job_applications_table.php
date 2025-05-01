<?php

use App\Models\Job;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            
            // Using foreignId() for better readability
            $table->foreignId('job_id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            // Changed applicant_id to user_id for Laravel conventions
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->comment('Applicant user ID');
            
            $table->foreignId('company_id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            $table->string('cv_file');
            $table->text('cover_letter')->nullable();
            
            $table->string('status')
                  ->default(Job::STATUS_PENDING)
                  ->index();
            
            $table->timestamps();

            // Composite unique index
            $table->unique(['job_id', 'user_id']);
            
            // Additional indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['company_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
}