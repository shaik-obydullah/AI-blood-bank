<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    protected string $baseUrl;
    protected string $model;

    public function __construct()
    {
        $this->baseUrl = config('ai.providers.ollama.url', 'http://localhost:11434/api');
        $this->model = config('ai.models.text', 'llama3.2:3b');
    }

    /**
     * Send a prompt to Ollama and get a response
     */
    public function prompt(string $prompt, array $options = []): string
    {
        try {
            $response = Http::timeout(120)
                ->post("{$this->baseUrl}/generate", [
                    'model' => $options['model'] ?? $this->model,
                    'prompt' => $prompt,
                    'stream' => false,
                    'options' => [
                        'temperature' => $options['temperature'] ?? 0.7,
                        'top_p' => $options['top_p'] ?? 0.9,
                    ],
                ]);

            if ($response->successful()) {
                return $response->json('response', 'Unable to get response from AI.');
            }

            Log::error('Ollama API error:', ['status' => $response->status()]);
            return 'AI service temporarily unavailable. Please try again later.';
        } catch (\Exception $e) {
            Log::error('Ollama connection error:', ['message' => $e->getMessage()]);
            return 'AI service is not available. Please ensure Ollama is running.';
        }
    }

    /**
     * Check donor eligibility using AI
     */
    public function checkDonorEligibility(array $donorData): array
    {
        $age = $donorData['age'];
        $hemoglobin = $donorData['hemoglobin'];
        $systolic = $donorData['systolic'];
        $diastolic = $donorData['diastolic'];
        $lastDonation = $donorData['last_donation'] ?? 'Never';
        $gender = $donorData['gender'] ?? 'Not specified';

        $prompt = <<<PROMPT
        You are a medical eligibility checker for a blood bank. Evaluate if this donor is eligible to donate blood.

        Donor Information:
        - Age: {$age} years
        - Hemoglobin Level: {$hemoglobin} g/dL
        - Blood Pressure: {$systolic}/{$diastolic} mmHg
        - Last Donation: {$lastDonation}
        - Gender: {$gender}

        Medical Guidelines for Blood Donation:
        - Age: Must be 18-65 years
        - Hemoglobin: Males 13.5-17.5 g/dL, Females 12.5-16.5 g/dL
        - Blood Pressure: Systolic 90-180, Diastolic 50-100 mmHg
        - Last donation: At least 3 months (90 days) gap
        - No recent infections, tattoos, or piercings

        Provide your evaluation in this exact JSON format:
        {
            "eligible": true/false,
            "status": "Eligible" or "Not Eligible",
            "reasons": ["reason1", "reason2"],
            "recommendations": ["recommendation1", "recommendation2"]
        }
        PROMPT;

        $response = $this->prompt($prompt, ['temperature' => 0.3]);

        $jsonMatch = [];
        preg_match('/\{[\s\S]*\}/', $response, $jsonMatch);

        if (!empty($jsonMatch[0])) {
            $decoded = json_decode($jsonMatch[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return [
            'eligible' => null,
            'status' => 'Unable to determine',
            'reasons' => ['AI could not process the request properly'],
            'recommendations' => ['Please consult with medical staff'],
        ];
    }

    /**
     * Prioritize blood distribution requests
     */
    public function prioritizeRequests(array $requests): array
    {
        $requestsJson = json_encode($requests, JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
        You are a blood distribution priority engine. Rank these blood requests by urgency.

        Requests: {$requestsJson}

        Consider these factors:
        1. Urgency level (emergency > urgent > routine)
        2. Blood type稀缺性 (O- is rarest, AB+ is most common)
        3. Units requested vs available
        4. Patient condition (critical > stable)
        5. Request time (older requests get priority)

        Return ranked list in this JSON format:
        {
            "ranked": [
                {
                    "request_id": 1,
                    "priority_score": 85,
                    "urgency": "high",
                    "reason": "Emergency request for rare blood type"
                }
            ]
        }
        PROMPT;

        $response = $this->prompt($prompt, ['temperature' => 0.5]);

        $jsonMatch = [];
        preg_match('/\{[\s\S]*\}/', $response, $jsonMatch);

        if (!empty($jsonMatch[0])) {
            $decoded = json_decode($jsonMatch[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return ['ranked' => []];
    }

    /**
     * Match donors for a blood request
     */
    public function matchDonors(array $request, array $availableDonors): array
    {
        $requestJson = json_encode($request, JSON_PRETTY_PRINT);
        $donorsJson = json_encode($availableDonors, JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
        Match the best donors for this blood request.

        Blood Request: {$requestJson}
        Available Donors: {$donorsJson}

        Consider:
        1. Blood type compatibility
        2. Donor eligibility (age, health, last donation date)
        3. Geographic proximity if available
        4. Donor availability and history

        Return matched donors in this JSON format:
        {
            "matches": [
                {
                    "donor_id": 1,
                    "match_score": 95,
                    "reason": "Perfect blood type match, recently eligible"
                }
            ]
        }
        PROMPT;

        $response = $this->prompt($prompt, ['temperature' => 0.4]);

        $jsonMatch = [];
        preg_match('/\{[\s\S]*\}/', $response, $jsonMatch);

        if (!empty($jsonMatch[0])) {
            $decoded = json_decode($jsonMatch[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return ['matches' => []];
    }

    /**
     * Answer donor FAQ
     */
    public function answerDonorQuestion(string $question): string
    {
        $prompt = <<<PROMPT
        You are a helpful blood bank assistant. Answer this question about blood donation.

        Question: {$question}

        Provide a clear, concise, and accurate answer. If you're not sure, recommend
        consulting with medical staff. Keep your answer under 200 words.

        Common facts:
        - Eligible age: 16-65 (with parental consent for 16-17)
        - Minimum wait between donations: 56 days (whole blood), 7 days (platelets)
        - Hemoglobin requirement: Males >= 13.5 g/dL, Females >= 12.5 g/dL
        - Blood pressure: 90-180/50-100 mmHg
        - Weight: At least 110 lbs (50 kg)
        PROMPT;

        return $this->prompt($prompt, ['temperature' => 0.7]);
    }

    /**
     * Check if Ollama is running
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/tags");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * List available models
     */
    public function listModels(): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/tags");

            if ($response->successful()) {
                return $response->json('models', []);
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
