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
        Schema::table('job_applications', function (Blueprint $table) {

            $table->string('offer_url')->nullable()->change();
            $table->string('notes')->nullable()->change();
            $table->integer('offered_salary_from')->nullable()->change();
            $table->integer('offered_salary_to')->nullable()->change();
            $table->integer('expected_salary_from')->nullable()->change();
            $table->integer('expected_salary_to')->nullable()->change();
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('offer_url')->nullable(false)->change();
            $table->string('notes')->nullable(false)->change();
            $table->integer('offered_salary_from')->nullable(false)->change();
            $table->integer('offered_salary_to')->nullable(false)->change();
            $table->integer('expected_salary_from')->nullable(false)->change();
            $table->integer('expected_salary_to')->nullable(false)->change();
        });
    }
};
