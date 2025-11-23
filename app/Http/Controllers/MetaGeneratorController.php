<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MetaGeneratorController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'))->keys();
    }

    public function generate(Request $request)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:5000',
            ]);

            $content = trim($request->input('content', ''));

            $languagesList = $this->locales->implode('|');

            $payload = [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' =>
                        'Return ONLY valid JSON. No explanations or markdown. Arrays must contain objects by language.',
                    ],
                    [
                        'role' => 'user',
                        'content' => <<<TXT
                        Generate SEO metadata in ALL of these languages: {$this->locales->implode(', ')}.

                        Return JSON ONLY in this EXACT structure:

                        {
                            "title": [
                                { "lang": "{$languagesList}", "value": "SEO title 50–60 chars" }
                            ],
                            "description": [
                                { "lang": "{$languagesList}", "value": "SEO description 150–160 chars" }
                            ],
                            "keywords": [
                                {
                                    "lang": "{$languagesList}",
                                    "value": ["keyword1", "keyword2", "... up to 10"]
                                }
                            ],
                            "content": [
                                {
                                    "lang": "{$languagesList}",
                                    "value": "HTML <section> content 300–450 words"
                                }
                            ]
                        }

                        Content rules:
                        - titles: 50–60 chars
                        - descriptions: 150–160 chars
                        - keywords: 5–10 very short SEO keywords
                        - html content:
                            * 3–10 <section> blocks
                            * each with <h2> and one short <p> (≤70 words)
                            * optional <ul> up to 4 <li> items (≤15 words each)
                            * semantic HTML only, no CSS, no scripts
                            * translate content for each language

                        CONTENT:
                        {$content}
                        TXT
                    ],
                ],
                'max_tokens' => 1500,
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

            $text = preg_replace('/```(?:json)?/i', '', $text);
            $text = preg_replace('/\x{FEFF}|\x{200B}|\x{200C}|\x{200D}|\x{2060}/u', '', $text);
            $text = str_replace(['“', '”', '‘', '’'], ['"', '"', "'", "'"], $text);
            $text = preg_replace('/,\s*(\]|\})/', '$1', $text);
            $text = trim($text);

            $json = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return errors(
                    message: 'Model did not return valid JSON.',
                    errors: [
                        'error' => json_last_error_msg(),
                        'raw'   => $text
                    ]
                );
            }


            return success(data: ['data' => $json], message: 'Metadata generated successfully.');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
