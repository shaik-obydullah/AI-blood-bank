<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\BloodGroup;
use App\Models\BloodDistribution;
use App\Services\OllamaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiController extends Controller
{
    protected OllamaService $ollama;

    public function __construct(OllamaService $ollama)
    {
        $this->ollama = $ollama;
    }

    /**
     * Check donor eligibility using AI
     */
    public function checkEligibility(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:donors,id',
        ]);

        $donor = Donor::with('bloodGroup')->findOrFail($validated['donor_id']);

        $donorData = [
            'age' => $donor->age,
            'hemoglobin' => $donor->hemoglobin_level,
            'systolic' => $donor->systolic,
            'diastolic' => $donor->diastolic,
            'last_donation' => $donor->last_donation_date?->format('Y-m-d') ?? 'Never',
            'gender' => 'Not specified',
        ];

        $result = $this->ollama->checkDonorEligibility($donorData);

        return response()->json([
            'donor' => [
                'id' => $donor->id,
                'name' => $donor->name,
                'blood_group' => $donor->bloodGroup->name ?? 'Unknown',
            ],
            'ai_result' => $result,
            'system_eligibility' => [
                'eligible' => $donor->isEligibleForDonation(),
                'medically_eligible' => $donor->isMedicallyEligible(),
            ],
        ]);
    }

    /**
     * Prioritize blood distribution requests
     */
    public function prioritizeRequests(): JsonResponse
    {
        $pendingRequests = BloodDistribution::with(['patient', 'bloodGroup'])
            ->whereNull('approved_unit')
            ->get()
            ->map(function ($dist) {
                return [
                    'id' => $dist->id,
                    'patient' => $dist->patient->name ?? 'Unknown',
                    'blood_group' => $dist->bloodGroup->name ?? 'Unknown',
                    'request_unit' => $dist->request_unit,
                    'created_at' => $dist->created_at->format('Y-m-d H:i:s'),
                ];
            })
            ->toArray();

        if (empty($pendingRequests)) {
            return response()->json([
                'message' => 'No pending requests to prioritize',
                'ranked' => [],
            ]);
        }

        $result = $this->ollama->prioritizeRequests($pendingRequests);

        return response()->json([
            'total_requests' => count($pendingRequests),
            'prioritized' => $result,
        ]);
    }

    /**
     * Match donors for a blood request
     */
    public function matchDonors(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'blood_group_id' => 'required|exists:blood_groups,id',
            'units_needed' => 'required|integer|min:1',
        ]);

        $bloodGroup = BloodGroup::find($validated['blood_group_id']);

        $availableDonors = Donor::where('fk_blood_group_id', $validated['blood_group_id'])
            ->get()
            ->map(function ($donor) {
                return [
                    'id' => $donor->id,
                    'name' => $donor->name,
                    'eligible' => $donor->isEligibleForDonation(),
                    'last_donation' => $donor->last_donation_date?->format('Y-m-d'),
                    'age' => $donor->age,
                ];
            })
            ->toArray();

        $request = [
            'blood_type' => $bloodGroup->name,
            'units_needed' => $validated['units_needed'],
        ];

        $result = $this->ollama->matchDonors($request, $availableDonors);

        return response()->json([
            'blood_group' => $bloodGroup->name,
            'units_needed' => $validated['units_needed'],
            'available_donors' => count($availableDonors),
            'matches' => $result,
        ]);
    }

    /**
     * Donor FAQ chat
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
        ]);

        $answer = $this->ollama->answerDonorQuestion($validated['question']);

        return response()->json([
            'question' => $validated['question'],
            'answer' => $answer,
        ]);
    }

    /**
     * Check Ollama status
     */
    public function status(): JsonResponse
    {
        $isAvailable = $this->ollama->isAvailable();
        $models = $isAvailable ? $this->ollama->listModels() : [];

        return response()->json([
            'available' => $isAvailable,
            'models' => $models,
            'configured_model' => config('ai.models.text'),
        ]);
    }
}
