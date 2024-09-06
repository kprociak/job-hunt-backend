<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\RecruitmentEvent;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecruitmentEventControllerTest extends TestCase
{

    private string $token;

    private int $jobApplicationId;

    private int $recruitmentEventId;

    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestSeeder::class);
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'test@example.dev',
            'password' => 'password1234',
        ]);

        $this->token = $loginResponse->json()['access_token'];
        $this->jobApplicationId = JobApplication::first()->id;
        $this->recruitmentEventId = RecruitmentEvent::first()->id;

    }

    public function testIndexMethodReturnsRecruitmentEvents()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/recruitment-events');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'recruitmentEvents',
        ]);
    }

    public function testIndexMethodReturnsRecruitmentEventsForJobApplication()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/job-applications/' . $this->jobApplicationId . '/recruitment-events');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'recruitmentEvents',
        ]);
    }

    public function testStoreMethodReturnsErrorWhenNoJobApplicationIdProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/recruitment-events', [
            'job_application_id' => '',
            'date' => date('Y-m-d'),
            'type' => 'Test Type',
        ]);
        $response->assertStatus(422);
    }

    public function testStoreMethodReturnsErrorWhenNoDateProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/recruitment-events', [
            'job_application_id' => $this->jobApplicationId,
            'date' => '',
            'type' => 'Test Type',
        ]);
        $response->assertStatus(422);
    }

    public function testStoreMethodReturnsErrorWhenNoTypeProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/recruitment-events', [
            'job_application_id' => $this->jobApplicationId,
            'date' => date('Y-m-d'),
            'type' => '',
        ]);
        $response->assertStatus(422);
    }

    public function testStoreMethodReturnsRecruitmentEvent()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/recruitment-events', [
            'job_application_id' => $this->jobApplicationId,
            'date' => date('Y-m-d'),
            'type' => 'Test Type',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'recruitmentEvent',
        ]);
    }

    public function testUpdateMethodReturnsErrorWhenNoDateProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/recruitment-events/' . $this->recruitmentEventId, [
            'date' => '',
            'type' => 'Test Type',
        ]);
        $response->assertStatus(422);
    }

    public function testUpdateMethodReturnsErrorWhenNoTypeProvided()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/recruitment-events/' . $this->recruitmentEventId, [
            'date' => date('Y-m-d'),
            'type' => '',
        ]);
        $response->assertStatus(422);
    }

    public function testUpdateMethodReturnsRecruitmentEvent()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/recruitment-events/' . $this->recruitmentEventId, [
            'date' => date('Y-m-d'),
            'type' => 'Test Type',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'recruitmentEvent',
        ]);
    }

    public function testDeleteMethodReturnsErrorWhenRecruitmentEventNotFound()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/recruitment-events/100');
        $response->assertStatus(404);
    }

    public function testDeleteMethodReturnsNoContent()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/recruitment-events/' . $this->recruitmentEventId);
        $response->assertStatus(204);
    }
}
