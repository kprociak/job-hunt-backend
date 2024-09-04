<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JobApplicationController extends Controller
{
    //
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $jobApplications = $user->jobApplications()->get();

        return response()->json([
            'jobApplications' => $jobApplications,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'company_name' => 'required|string',
                'job_title' => 'required|string',

            ]);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'The provided data is invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = $request->user();
        $jobApplication = $user->jobApplications()->create(array_merge($request->all(), ['status' => 'new']));

        return response()->json([
            'jobApplication' => $jobApplication,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'company_name' => 'required|string',
                'job_title' => 'required|string',
            ]);
        }
        catch (ValidationException $e) {
            return response()->json([
                'error' => 'The provided data is invalid.',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = $request->user();
        $jobApplication = $user->jobApplications()->findOrFail($id);
        $jobApplication->update($request->all());

        return response()->json([
            'jobApplication' => $jobApplication,
        ]);
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $jobApplication = $user->jobApplications()->findOrFail($id);
        $jobApplication->delete();

        return response()->json([
        ], 204);
    }

}
