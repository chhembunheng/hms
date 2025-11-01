<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MetaGeneratorController extends Controller
{
    public function generate(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:5000',
            ]);
            $content = trim((string) $request->input('content', ''));
            $payload = [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Return ONLY valid JSON. No extra text or Markdown formatting.',
                    ],
                    [
                        'role' => 'user',
                        'content' => <<<TXT
                                            From the CONTENT below, generate SEO fields and a Details section.
                                            Return JSON ONLY in this exact shape:
                                            {
                                                "title": "",
                                                "description": "",
                                                "keywords": [],
                                                "content": ""
                                            }
                                            Constraints:
                                            - title: 50–60 chars
                                            - description: 150–160 chars
                                            - keywords: array of 5–10 short SEO keywords
                                            - content: semantic HTML for a "Details" section that fits ~1 A4 page when printed (≈300–450 words total).
                                                * 3–10 subsections.
                                                * Use <section> with a <h2> per subsection.
                                                * Each subsection: 1 short <p> (≤70 words).
                                                * Optional <ul> with ≤4 <li> items; each <li> ≤15 words.
                                                * No images, no external links, no inline styles, no scripts.
                                                * English; concise and scannable.
                                            CONTENT:
                                            {$content}
                                            TXT
                    ],
                ],
                'max_tokens' => 900,
                'temperature' => 0.3,
            ];
            $resp = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ])
                ->timeout(15)
                ->connectTimeout(5)
                ->post('https://api.openai.com/v1/chat/completions', $payload);

            if ($resp->failed()) {
                return response()->json(['error' => $resp->json() ?: $resp->body()], 502);
            }
            $text = data_get($resp->json(), 'choices.0.message.content', '');
            $json = json_decode($text, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($json)) {
                return errors(message: 'Model did not return valid JSON.', errors: [$text]);
            }
            $json += ['title' => '', 'description' => '', 'keywords' => [], 'content' => ''];
            return success(data: ['data' => $json], message: 'Metadata generated successfully.');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
