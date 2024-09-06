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
        Schema::table('recruitment_events', function (Blueprint $table) {
            //
            $table->dropForeign('recruitment_events_job_application_id_foreign');
            $table->foreign('job_application_id')->references('id')->on('job_applications')->onDelete('cascade');
            $table->dropForeign('recruitment_events_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitment_events', function (Blueprint $table) {
            //
        });
    }
};
