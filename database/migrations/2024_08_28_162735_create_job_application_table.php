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
        Schema::create('job_application', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('company_name');
            $table->string('job_title');
            $table->string('offer_url');
            $table->string('status');
            $table->date('application_date');
            $table->string('notes');
            $table->integer('offered_salary_from');
            $table->integer('offered_salary_to');
            $table->integer('expected_salary_from');
            $table->integer('expected_salary_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_application');
    }
};
