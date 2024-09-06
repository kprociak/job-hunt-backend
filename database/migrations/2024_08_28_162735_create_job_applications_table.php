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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('job_title');
            $table->string('offer_url')->nullable();
            $table->string('status')->default('new');
            $table->date('application_date');
            $table->string('notes')->nullable();
            $table->integer('offered_salary_from')->nullable();
            $table->integer('offered_salary_to')->nullable();
            $table->integer('expected_salary_from')->nullable();
            $table->integer('expected_salary_to')->nullable();
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
