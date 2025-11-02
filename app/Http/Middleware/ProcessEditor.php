<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProcessEditor
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->processRequestData($request);

        return $next($request);
    }

    private function processRequestData(Request $request): void
    {
        if ($request->isJson()) {
            $data = $request->json()->all();
            $data = $this->processArray($data);
            $request->json()->replace($data);
        }

        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('patch')) {
            $data = $request->all();
            $data = $this->processArray($data);
            $request->merge($data);
        }
    }

    private function processArray($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data[$key] = $this->processHtml($value);
                } elseif (is_array($value) || is_object($value)) {
                    $data[$key] = $this->processArray($value);
                }
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data->{$key} = $this->processHtml($value);
                } elseif (is_array($value) || is_object($value)) {
                    $data->{$key} = $this->processArray($value);
                }
            }
        }

        return $data;
    }

    private function processHtml(string $html): string
    {
        $pattern = '/<img\s+([^>]*?)src=["\']data:image\/([a-z]+);base64,([A-Za-z0-9+\/=]+)["\']([^>]*)>/i';

        return preg_replace_callback($pattern, function ($matches) {
            $beforeSrc = $matches[1];
            $imageType = $matches[2];
            $base64Data = $matches[3];
            $afterSrc = $matches[4];

            $imageData = base64_decode($base64Data, true);
            if ($imageData === false) {
                return $matches[0];
            }

            $filename = 'editor-images/' . uniqid('img_', true) . '.' . $imageType;

            try {
                Storage::disk('public')->put($filename, $imageData);
                return '<img ' . $beforeSrc . 'src="/storage/' . $filename . '"' . $afterSrc . '>';
            } catch (\Exception $e) {
                Log::warning('Failed to process base64 image: ' . $e->getMessage());
                return $matches[0];
            }
        }, $html);
    }
}
