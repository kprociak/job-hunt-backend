<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RecruitmentEventController extends Controller
{
    //
    public function index(Request $request, int $jobApplicationId): JsonResponse
    {
        $user = $request->user();
        if ($jobApplicationId) {
            $jobApplication = $user->jobApplications()->find($jobApplicationId);
            $recruitmentEvents = $jobApplication->recruitmentEvents()->get();
            Log::log('info', 'Recruitment events for job application ' . $jobApplication->id . ' were retrieved.');
        } else {
            $recruitmentEvents = $user->recruitmentEvents()->get();
            Log::log('info', 'All recruitment events were retrieved.'.json_encode($request->all()));
        }

        return response()->json([
            'recruitmentEvents' => $recruitmentEvents,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'job_application_id' => 'required|integer',
                'date' => 'required|date',
                'type' => 'required|string',
            ]);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'The provided data is invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = $request->user();
        $jobApplication = $user->jobApplications()->find($request->job_application_id);
        $recruitmentEvent = new RecruitmentEvent($request->all());
        $recruitmentEvent->user()->associate($user);
        $recruitmentEvent->jobApplication()->associate($jobApplication);
        $recruitmentEvent->save();

        $jobApplication->status = match ($recruitmentEvent->type) {
            'offer' => 'offer',
            'rejection' => 'rejected',
            default => 'ongoing',
        };
        $jobApplication->save();


        return response()->json([
            'recruitmentEvent' => $recruitmentEvent,
        ], 201);
    }
}
