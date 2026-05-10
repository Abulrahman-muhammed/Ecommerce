<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class GenerateProductContentService
{
    protected string $geminiApiKey;

    public function __construct()
    {
        $this->geminiApiKey = env('GEMINI_API_KEY');
    }

    public function generateContent(string $productName, array $categories = [], array $tags = []): ?array
    {
        // ── حوّل الـ arrays لـ JSON نص عشان يتحط في الـ prompt ──
        $categoriesJson = json_encode($categories, JSON_PRETTY_PRINT);
        $tagsJson       = json_encode($tags,       JSON_PRETTY_PRINT);

        $prompt = "
You are an ecommerce assistant. Given a product name and lists of available categories and tags,
generate the product content and pick the most relevant category and tags FROM THE PROVIDED LISTS ONLY.
Do NOT invent new categories or tags. Return only IDs that exist in the lists.

Product name: \"{$productName}\"

Available categories:
{$categoriesJson}

Available tags:
{$tagsJson}

Return ONLY valid JSON, no markdown, exactly this format:
{
    \"description\": \"2-3 engaging sentences highlighting key features and benefits, max 80 words\",
    \"category_id\": <the single most relevant category id as integer, or null if none fits>,
    \"tag_ids\": [<array of relevant tag ids as integers, pick 2-5 max>]
}
        ";

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$this->geminiApiKey}",
            [
                'contents' => [
                    [
                        'parts' => [['text' => $prompt]]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                ],
            ]
        );

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$text) {
            return null;
        }

        $text = str_replace(['```json', '```'], '', $text);

        return json_decode(trim($text), true);
    }
}