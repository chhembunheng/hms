<?php

namespace App\Traits;

use App\Models\SEO;

trait HasSEO
{
    public function seo()
    {
        return $this->morphOne(SEO::class, 'model');
    }

    public function updateSEO(array $data)
    {
        return $this->seo()->updateOrCreate([], $data);
    }

    public function getSEO(): ?SEO
    {
        return $this->seo;
    }

    public function isSEOPublished(): bool
    {
        return (bool) ($this->seo?->is_published);
    }

    public function getSEOScore(): int
    {
        return (int) ($this->seo?->seo_score ?? 0);
    }

    public function getSEOHealth(): string
    {
        return $this->seo?->getHealthStatus() ?? 'none';
    }

    public function generateSEO(): void
    {
        $meta = request()->input('meta', []);

        $seoData = [
            'meta_title' => $meta['title'],
            'meta_description' => $meta['description'],
            'meta_keywords' => is_array($meta['keywords']) ? implode(', ', $meta['keywords']) : $meta['keywords'],
            'lang' => 'en',
            'og_title' => $meta['title'],
            'og_description' => $meta['description'],
            'og_type' => 'article',
            'og_image' => $this->image,
            'canonical_url' => $this->getCanonicalUrl(),
            'slug' => slug($this->getCanonicalUrl()),
            'is_published' => true,
            'seo_status' => 'published',
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        $seo = $this->updateSEO($seoData);

        if (method_exists($seo, 'updateSEOScore')) {
            $seo->updateSEOScore();
        }
    }
}