<?php

namespace Database\Seeders;

use App\Models\RecruitmentEvent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.dev',
            'password' => \Hash::make('password1234'),
        ]);

        $application = $user->jobApplications()->create([
            'company_name' => 'Test Company',
            'job_title' => 'Test Job',
            'status' => 'new',
            'application_date' => now(),
        ]);

        $recruitmentEvent = new RecruitmentEvent([
            'date' => now(),
            'type' => 'Test Type',
        ]);
        $recruitmentEvent->jobApplication()->associate($application);
        $recruitmentEvent->user()->associate($user);
        $recruitmentEvent->save();



    }
}
