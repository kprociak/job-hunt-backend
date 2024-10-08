<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobApplicationControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $token;
    private int $jobApplicationId;
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed(TestSeeder::class);
        $this->token = $this->postJson('/api/login', [
            'email' => 'test@example.dev',
            'password' => 'password1234',
        ])->json()['access_token'];

        $this->jobApplicationId = JobApplication::first()->id;

    }

    public function testIndexMethodReturnsJobApplications()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/job-applications');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'jobApplications',
        ]);
    }

    public function testStoreMethodReturnsErrorWhenNoCompanyNameProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/job-applications', [
            'company_name' => '',
            'job_title' => 'Test Job',
            'application_date' => date('Y-m-d'),
        ]);

        $response->assertStatus(422);
    }

    public function testStoreMethodReturnsErrorWhenNoJobTitleProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/job-applications', [
            'company_name' => 'Test Company',
            'job_title' => '',
            'application_date' => date('Y-m-d'),
        ]);
        $response->assertStatus(422);
    }

    public function testStoreMethodReturnsErrorWhenNoApplicationDateProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/job-applications', [
            'company_name' => 'Test Company',
            'job_title' => 'Test Job',
            'application_date' => '',
        ]);

        $response->assertStatus(422);
    }

    public function testStoreMethodReturnsJobApplication()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/job-applications', [
            'company_name' => 'Test Company',
            'job_title' => 'Test Job',
            'application_date' => date('Y-m-d'),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'jobApplication',
        ]);
    }

    public function testThatInputDataIsSanitized()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/job-applications', [
            'company_name' => '<strong>Test Company</strong>',
            'job_title' => '<span>Test Job</span>',
            'application_date' => date('Y-m-d'),
            'offer_url' => 'https://example.dev',
            'offered_salary_from' => 1000,
            'offered_salary_to' => 2000,
            'expected_salary_from' => 3000,
            'expected_salary_to' => 4000,
            'notes' => 'Test notes<script>alert("XSS")</script>',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'jobApplication',
        ]);
        $response->assertJson([
            'jobApplication' => [
                'company_name' => 'Test Company',
                'job_title' => 'Test Job',
                'application_date' => date('Y-m-d'),
                'offer_url' => 'https://example.dev',
                'offered_salary_from' => 1000,
                'offered_salary_to' => 2000,
                'expected_salary_from' => 3000,
                'expected_salary_to' => 4000,
                'notes' => 'Test notes',
            ]
        ]);
    }


    public function testUpdateMethodReturnsErrorWhenNoCompanyNameProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/job-applications/1', [
            'company_name' => '',
            'job_title' => 'Test Job',
        ]);

        $response->assertStatus(422);
    }

    public function testUpdateMethodReturnsErrorWhenNoJobTitleProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/job-applications/1', [
            'company_name' => 'Test Company',
            'job_title' => '',
        ]);

        $response->assertStatus(422);
    }

    public function testUpdateMethodReturnsErrorWhenJobApplicationNotFound()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/job-applications/100', [
            'company_name' => 'Test Company',
            'job_title' => 'Test Job',
        ]);

        $response->assertStatus(404);
    }

    public function testUpdateMethodReturnsJobApplication()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/job-applications/'.$this->jobApplicationId, [
            'company_name' => 'Test Company',
            'job_title' => 'Test Job',
            'application_date' => date('Y-m-d'),
            'offer_url' => 'http://example.dev',
            'offered_salary_from' => 1000,
            'offered_salary_to' => 2000,
            'expected_salary_from' => 3000,
            'expected_salary_to' => 4000,
            'notes' => 'Test notes',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'jobApplication',
        ]);
    }

    public function testDeleteMethodReturnsErrorWhenJobApplicationNotFound()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/job-applications/100');

        $response->assertStatus(404);
    }


    public function testDeleteMethodReturnsNoContentStatus()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/job-applications/'.$this->jobApplicationId);

        $response->assertStatus(204);
    }

}
