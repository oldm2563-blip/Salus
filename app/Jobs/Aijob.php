<?php

namespace App\Jobs;

use App\Models\Ai_Respond;
use App\Models\Symptom;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Aijob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    private int $symptomId;
    private int $id;
    public function __construct($symptomId, $userId)
    {
        $this->symptomId = (int) $symptomId;
        $this->id = (int) $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $Symptom = Symptom::find($this->symptomId);
        if (!$Symptom) {
            Log::error('Symptom not found', ['id' => $this->symptomId]);
            return;
        }
        $notes = '';
        
         $prompt = "You are a helpful health assistant.

         Name: {$Symptom->name}
         Description: {$Symptom->description}
         Severity: {$Symptom->severity}
         Notes: {$notes}

         Provide general wellness advice in a friendly tone.
         Do NOT provide medical diagnosis or prescriptions.
         Limit response to 20 words.";

         $response = Http::withHeaders([
            'content-type' => 'application/json'
         ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . env('GEMINI_API_KEY'), [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => $prompt
                        ]
                    ]
                ]
            ]
         ]);

         if ($response->failed()) {
            Log::error('Gemini API failed', [
                'response' => $response->body()
            ]);
            return;
        }
         $data = $response->json();

            Log::info('Gemini response', ['data' => $data]);

         $advice = $data['candidates'][0]["content"]["parts"][0]["text"] ?? "no advice was generated";

         Log::info('Advice extracted', ['advice' => $advice]);

         Ai_Respond::create([
            'response' => $advice,
            'generated_at' => Carbon::now(),
            'user_id' => $this->id
         ]);

         Log::info('Ai_Respond saved', ['user_id' => $this->id]);
    }
}
