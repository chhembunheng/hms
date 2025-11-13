<?php

namespace App\Traits;

trait HasSEO
{
    /**
     * Get the SEO record for this model
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(SEORecord::class, 'model');
    }

    /**
     * Create or update SEO record
     */
    public function updateSEO(array $data): SEORecord
    {
        return $this->seo()->updateOrCreate(
            ['model_type' => static::class, 'model_id' => $this->id],
            $data
        );
    }

    /**
     * Get SEO data
     */
    public function getSEOData(): ?SEORecord
    {
        return $this->seo;
    }

    /**
     * Check if SEO is published
     */
    public function isSEOPublished(): bool
    {
        return $this->seo?->is_published ?? false;
    }

    /**
     * Get SEO score
     */
    public function getSEOScore(): int
    {
        return $this->seo?->seo_score ?? 0;
    }

    /**
     * Get SEO health status
     */
    public function getSEOHealth(): string
    {
        return $this->seo?->getHealthStatus() ?? 'none';
    }

    /**
     * Generate default SEO data from model attributes
     */
    public function generateDefaultSEO(): void
    {
        $title = $this->getAttribute('title') ?? $this->getAttribute('name');
        $description = $this->getAttribute('description') ?? $this->getAttribute('bio');
        $slug = $this->getAttribute('slug') ?? '';
        $seoData = [
            'meta_title' => $title ? substr($title, 0, 60) : null,
            'meta_description' => $description ? substr($description, 0, 160) : null,
            'og_title' => $title,
            'og_description' => $description,
            'og_type' => 'article',
            'is_published' => false,
            'seo_status' => 'draft',
        ];

        // Build canonical URL if slug exists
        if ($slug) {
            $modelName = strtolower(class_basename($this));
            $seoData['canonical_url'] = url("/{$modelName}/{$slug}");
        }

        $this->updateSEO($seoData);
        $this->seo->updateSEOScore();
    }
}
