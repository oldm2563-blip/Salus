<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Ai_Respond;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RespondController extends Controller
{
    public function ai_respond()
    {
        $now = Carbon::now();
        $Symptom = auth()->user()->symptoms()->take(3)->pluck('name')->implode(', ');
        
        $prompt = "
        You are a helpful health assistant.

        Symptoms {$Symptom}
        
        Provide general wellness advice.
        Do not give medical diagnosis.
        in 50 words";

        $respond = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . env('GEMINI_API_KEY') , [
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
        if($respond->successful()){
            $output = $respond->json()['candidates'][0]['content']['parts'][0]['text'];
        }
        else{
            return response()->json([
            "success" => false,
            "message" => "there was a problem with the ai"
        ]);
        }

        Ai_Respond::create([
            'response' => $output,
            'generated_at' => $now,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            "success" => true,
            "ai_response" => $output,
            "generated_at" => $now
        ]);
    }



    public function history()
    {
        $advices = auth()->user()->responses()->latest()->get();
        return response()->json([
            "success" => true,
            "data" => $advices,
            "message" => "history have been retrieved"
        ]);
    }
}
