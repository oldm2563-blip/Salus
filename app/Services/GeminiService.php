<?php
    namespace App\Services;
    use Illuminate\Support\Facades\Http;

    class GeminiService{
        public function ask($prompt)
        {
            $respond = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . config('services.gemini.key') , [
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
            if($respond->failed()){
                return[
                    'error' => 'There Was A problem With the AI'
                ];
            }
            return $respond->json()['candidates'][0]['content']['parts'][0]['text'];
        } 
    }
