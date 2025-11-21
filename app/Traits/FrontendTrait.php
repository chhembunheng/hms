<?php

namespace App\Traits;


trait FrontendTrait
{
    protected function getNavigationsForDropdown($excludeId = null)
    {
        $locale = app()->getLocale();
        $query = \App\Models\Frontend\Navigation::with('translations')
            ->orderBy('sort', 'asc');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $navigations = $query->get()->mapWithKeys(function ($nav) use ($locale) {
            return [$nav->id => $nav->getName($locale)];
        });
        return $navigations->toArray();
    }
    // tags like categories for dropdown
    protected function getTagsForDropdown()
    {
        $locale = app()->getLocale();
        $tags = \App\Models\Frontend\Tag::with('translations')
            ->orderBy('sort', 'asc')
            ->get()
            ->mapWithKeys(function ($tag) use ($locale) {
                return [$tag->id => $tag->getName($locale)];
            });
        return $tags->toArray();
    }
    // categories for dropdown
    protected function getCategoriesForDropdown()
    {
        $locale = app()->getLocale();
        $categories = \App\Models\Frontend\Category::with('translations')
            ->orderBy('sort', 'asc')
            ->get()
            ->mapWithKeys(function ($category) use ($locale) {
                return [$category->id => $category->getName($locale)];
            });
        return $categories->toArray();
    }
}
