<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\Aijob;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RespondController extends Controller
{
    // public function ai_respond()
    // {
    //     $now = Carbon::now();
    //     $symptoms = auth()->user()->symptoms()->latest()->first();
        
    //     if (!$symptoms) {
    //         return response()->json([
    //             "success" => false,
    //             "message" => "No symptoms found"
    //         ], 404);
    //     }

    //     $prompt = "
    //     You are a helpful health assistant.

    //     Name: {$symptoms->name}
    //     Description: {$symptoms->description}
    //     Severity: {$symptoms->severity}
    //     Notes: {$symptoms->notes}

    //     Provide general wellness advice in a friendly tone.
    //     Do NOT provide medical diagnosis or prescriptions.
    //     Limit response to 50 words.";

    //     $respond = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //         ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . config('services.gemini.key') , [
    //             "contents" => [
    //                 [
    //                     "parts" => [
    //                         [
    //                             "text" => $prompt
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]);
    //         if($respond->failed()){
    //             return[
    //                 'error' => 'There Was A problem With the AI'
    //             ];
    //         }
    //         $output = $respond->json()['candidates'][0]['content']['parts'][0]['text'];

    //     Ai_Respond::create([
    //         'response' => $output,
    //         'generated_at' => $now,
    //         'user_id' => auth()->id()
    //     ]);

    //     return response()->json([
    //         "success" => true,
    //         "ai_response" => $output,
    //         "generated_at" => $now
    //     ]);
    // }

    #[OA\Post(
        path: '/ai/health-advice',
        summary: 'Get ai Health advice',
        security: [['sanctum' => []]],
        tags: ['AI'],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]
    public function ai_respond()
    {
        $symptom = auth()->user()->symptoms()->latest()->first();
        $userId = auth()->id();
        Aijob::dispatch($symptom->id, $userId);  
        return response()->json([
            "advice" => "generating"
        ]);

    }

    #[OA\Get(
        path: '/ai/last-advice',
        summary: 'retrieve The latest advice',
        security: [['sanctum' => []]],
        tags: ['AI'],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]
    public function latest_advice()
    {
        $advices = auth()->user()->responses()->latest()->first();
        return response()->json([
            "success" => true,
            "data" => $advices,
            "message" => "history have been retrieved"
        ]);
    }

    #[OA\Get(
        path: '/ai/history',
        summary: 'Get The advice history',
        security: [['sanctum' => []]],
        tags: ['AI'],
        responses: [
            new OA\Response(response: 200, description: 'List of user symptoms'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Symptoms not found'),
        ]
    )]
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
