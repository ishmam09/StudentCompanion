<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Generate a quiz from file content (PDF/Text/Image)
     * 
     * @param string $fileContent Base64 encoded file content
     * @param string $mimeType Mime type of the file
     * @return array
     */
    public function generateQuiz($fileContent, $mimeType)
    {
        $prompt = "Generate a quiz with exactly 20 multiple-choice questions based on the provided document. 
        Return the response ONLY as a JSON array where each object has:
        - 'question' (string)
        - 'options' (array of 4 strings)
        - 'answer' (string, exactly matching one of the options)
        
        Do not include markdown formatting like ```json ... ```. Just the raw JSON array.";

        $response = Http::post("{$this->baseUrl}?key={$this->apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $fileContent
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'responseMimeType' => 'application/json'
            ]
        ]);

        if ($response->failed()) {
            Log::error('Gemini API Error: ' . $response->body());
            throw new \Exception('Failed to generate quiz via Gemini API.');
        }

        $responseData = $response->json();
        
        try {
            $text = $responseData['candidates'][0]['content']['parts'][0]['text'];
            // Clean up any potential markdown backticks if Gemini ignores the prompt
            $text = preg_replace('/^```json\s*|\s*```$/', '', $text);
            
            return json_decode($text, true);
        } catch (\Exception $e) {
            Log::error('Gemini Parsing Error: ' . $e->getMessage());
            throw new \Exception('Failed to parse Gemini response.');
        }
    }
}
