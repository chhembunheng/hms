<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportSiteJsonSeeder extends Seeder
{
    private string $dataPath;

    public function __construct()
    {
        $this->dataPath = base_path('public/site/data');
    }

    public function run(): void
    {
        $this->seedProducts();
        $this->seedServices();
        $this->seedBlogs();
        $this->seedTeams();
        $this->seedFaqs();
        $this->seedIntegrations();
        $this->seedClients();
        $this->seedPartners();
        $this->seedChoosings();
        $this->seedAchievements();
        $this->seedCareers();
    }

    private function readJson(string $locale, string $file): array
    {
        $path = $this->dataPath . '/' . $locale . '/' . $file;
        if (!File::exists($path)) {
            return [];
        }
        return json_decode(File::get($path), true) ?? [];
    }

    private function locales(): array
    {
        return ['en', 'km'];
    }

    private function uniqueSlug(string $table, string $slug): string
    {
        // Slug columns in main tables are globally unique.
        $final = $slug;
        $i = 1;
        while (DB::table($table)->where('slug', $final)->exists()) {
            $final = rtrim($slug, '-') . '-' . $i;
            $i++;
        }
        return $final;
    }

    private function seedProducts(): void
    {
        $en = $this->readJson('en', 'products.json');
        $km = $this->readJson('km', 'products.json');
        $byId = [
            'en' => collect($en)->keyBy('id'),
            'km' => collect($km)->keyBy('id'),
        ];

        $sort = 1;
        foreach ($byId['en'] as $id => $item) {
            $slug = Str::slug($item['slug'] ?? $item['name'] ?? (string) $id);
            $slug = $this->uniqueSlug('products', $slug);
            $sku = $item['sku'] ?? 'PROD-' . str_pad($id, 3, '0', STR_PAD_LEFT);

            // Try to find by SKU first, then by slug
            $productId = DB::table('products')->where('sku', $sku)->value('id');
            
            if (!$productId) {
                $productId = DB::table('products')->where('slug', $slug)->value('id');
            }

            if (!$productId) {
                $productId = DB::table('products')->insertGetId([
                    'sku' => $sku,
                    'slug' => $slug,
                    'image' => $item['thumb'] ?? $item['image'] ?? null,
                    'sort' => $sort,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('products')->where('id', $productId)->update([
                    'sku' => $sku,
                    'slug' => $slug,
                    'image' => $item['thumb'] ?? $item['image'] ?? null,
                    'sort' => $sort,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byId[$locale][$id] ?? null;
                if (!$t) continue;

                DB::table('product_translations')->updateOrInsert(
                    ['product_id' => $productId, 'locale' => $locale],
                    [
                        'name' => $t['name'] ?? ($locale === 'en' ? $item['name'] ?? 'N/A' : ''),
                        'short_description' => $t['short_description'] ?? null,
                        'description' => $t['description'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $sort++;
        }
    }

    private function seedServices(): void
    {
        $en = $this->readJson('en', 'services.json');
        $km = $this->readJson('km', 'services.json');
        $byId = [
            'en' => collect($en)->keyBy('id'),
            'km' => collect($km)->keyBy('id'),
        ];
        $sort = 1;
        foreach ($byId['en'] as $id => $item) {
            $slug = Str::slug($item['slug'] ?? $item['name'] ?? (string)$id);
            $slug = $this->uniqueSlug('services', $slug);
            
            $serviceId = DB::table('services')->where('slug', $slug)->value('id');

            if (!$serviceId) {
                $serviceId = DB::table('services')->insertGetId([
                    'slug' => $slug,
                    'icon' => $item['icon'] ?? null,
                    'image' => $item['image'] ?? null,
                    'sort' => $sort,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('services')->where('id', $serviceId)->update([
                    'slug' => $slug,
                    'icon' => $item['icon'] ?? null,
                    'image' => $item['image'] ?? null,
                    'sort' => $sort,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byId[$locale][$id] ?? null;
                if (!$t) continue;

                // NOTE: service_translations uses 'title' column (not 'name') per migration
                DB::table('service_translations')->updateOrInsert(
                    ['service_id' => $serviceId, 'locale' => $locale],
                    [
                        'name' => $t['name'] ?? ($locale === 'en' ? $item['name'] ?? 'N/A' : ''),
                        'short_description' => $t['short_description'] ?? null,
                        'description' => $t['description'] ?? null,
                        'content' => $t['content'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $sort++;
        }
    }

    private function seedBlogs(): void
    {
        $en = $this->readJson('en', 'articles.json');
        $km = $this->readJson('km', 'articles.json');
        $byIdx = [
            'en' => collect($en)->values(),
            'km' => collect($km)->values(),
        ];

        foreach ($byIdx['en'] as $i => $item) {
            $slug = Str::slug($item['slug'] ?? $item['title'] ?? (string)$i);
            $slug = $this->uniqueSlug('blogs', $slug);
            
            $blogId = DB::table('blogs')->where('slug', $slug)->value('id');

            if (!$blogId) {
                $blogId = DB::table('blogs')->insertGetId([
                    'slug' => $slug,
                    'author_id' => null, // Can't reliably map from string author name
                    'thumbnail' => $item['image'] ?? null,
                    'is_published' => true,
                    'sort' => $i + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('blogs')->where('id', $blogId)->update([
                    'slug' => $slug,
                    'thumbnail' => $item['image'] ?? null,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byIdx[$locale][$i] ?? null;
                if (!$t) continue;

                DB::table('blog_translations')->updateOrInsert(
                    ['blog_id' => $blogId, 'locale' => $locale],
                    [
                        'title' => $t['title'] ?? ($locale === 'en' ? $item['title'] ?? 'N/A' : ''),
                        'excerpt' => $t['excerpt'] ?? null,
                        'body' => $t['content'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }

    private function seedTeams(): void
    {
        $en = $this->readJson('en', 'teams.json');
        $km = $this->readJson('km', 'teams.json');
        $byId = [
            'en' => collect($en)->keyBy('id'),
            'km' => collect($km)->keyBy('id'),
        ];

        foreach ($byId['en'] as $id => $item) {
            // Match by English name if exists
            $teamId = DB::table('teams')
                ->join('team_translations as tt', 'tt.team_id', '=', 'teams.id')
                ->where('tt.locale', 'en')
                ->where('tt.name', $item['name'])
                ->value('teams.id');

            $socials = $item['socials'] ?? [];

            if (!$teamId) {
                $teamId = DB::table('teams')->insertGetId([
                    'photo' => $item['photo'] ?? null,
                    'linkedin_url' => $socials['linkedin'] ?? null,
                    'twitter_url' => $socials['twitter'] ?? null,
                    'facebook_url' => $socials['facebook'] ?? null,
                    'instagram_url' => $socials['instagram'] ?? null,
                    'github_url' => $socials['github'] ?? null,
                    'email' => $socials['email'] ?? null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('teams')->where('id', $teamId)->update([
                    'photo' => $item['photo'] ?? null,
                    'linkedin_url' => $socials['linkedin'] ?? null,
                    'twitter_url' => $socials['twitter'] ?? null,
                    'facebook_url' => $socials['facebook'] ?? null,
                    'instagram_url' => $socials['instagram'] ?? null,
                    'github_url' => $socials['github'] ?? null,
                    'email' => $socials['email'] ?? null,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byId[$locale][$id] ?? null;
                if (!$t) continue;
                DB::table('team_translations')->updateOrInsert(
                    ['team_id' => $teamId, 'locale' => $locale],
                    [
                        'name' => $t['name'] ?? ($locale === 'en' ? $item['name'] ?? 'N/A' : ''),
                        'position' => $t['position'] ?? null,
                        'bio' => $t['bio'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }

    private function seedFaqs(): void
    {
        $en = $this->readJson('en', 'faqs.json');
        $km = $this->readJson('km', 'faqs.json');

        $flatten = function(array $arr): array {
            $out = [];
            foreach ($arr as $group) {
                $catSlug = $group['slug'] ?? Str::slug($group['name'] ?? 'faq');
                foreach ($group['faqs'] ?? [] as $faq) {
                    $faq['__cat_slug'] = $catSlug;
                    $out[] = $faq;
                }
            }
            return $out;
        };

        $enFlat = collect($flatten($en))->keyBy('id');
        $kmFlat = collect($flatten($km))->keyBy('id');

        $sort = 1;
        foreach ($enFlat as $id => $faq) {
            $slug = Str::slug(($faq['__cat_slug'] ?? 'faq') . '-' . ($faq['question'] ?? $id));
            $slug = $this->uniqueSlug('faqs', $slug);

            $faqId = DB::table('faqs')->where('slug', $slug)->value('id');

            if (!$faqId) {
                $faqId = DB::table('faqs')->insertGetId([
                    'slug' => $slug,
                    'is_published' => true,
                    'sort' => $sort,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('faqs')->where('id', $faqId)->update([
                    'slug' => $slug,
                    'sort' => $sort,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $src = $locale === 'en' ? $enFlat : $kmFlat;
                $t = $src[$id] ?? null;
                if (!$t) continue;

                DB::table('faq_translations')->updateOrInsert(
                    ['faq_id' => $faqId, 'locale' => $locale],
                    [
                        'question' => $t['question'] ?? ($locale === 'en' ? $faq['question'] ?? 'N/A' : ''),
                        'answer' => $t['answer'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            $sort++;
        }
    }

    private function seedIntegrations(): void
    {
        $en = $this->readJson('en', 'integrations.json');
        $km = $this->readJson('km', 'integrations.json');
        $flatten = function(array $arr): array {
            $out = [];
            foreach ($arr as $group) {
                foreach ($group['integrations'] ?? [] as $item) {
                    $out[] = $item;
                }
            }
            return $out;
        };
        $enFlat = collect($flatten($en))->keyBy('slug');
        $kmFlat = collect($flatten($km))->keyBy('slug');

        $sort = 1;
        foreach ($enFlat as $slug => $item) {
            // Try to find by English name
            $integrationId = DB::table('integrations')
                ->join('integration_translations as it', 'it.integration_id', '=', 'integrations.id')
                ->where('it.locale', 'en')
                ->where('it.name', $item['name'])
                ->value('integrations.id');

            if (!$integrationId) {
                $integrationId = DB::table('integrations')->insertGetId([
                    'logo' => $item['image'] ?? null,
                    'url' => $item['url'] ?? null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('integrations')->where('id', $integrationId)->update([
                    'logo' => $item['image'] ?? null,
                    'url' => $item['url'] ?? null,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = ($locale === 'en' ? $enFlat : $kmFlat)[$slug] ?? null;
                if (!$t) continue;
                DB::table('integration_translations')->updateOrInsert(
                    ['integration_id' => $integrationId, 'locale' => $locale],
                    [
                        'name' => $t['name'] ?? ($locale === 'en' ? $item['name'] ?? 'N/A' : ''),
                        'description' => $t['tagline'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $sort++;
        }
    }

    private function seedClients(): void
    {
        $en = $this->readJson('en', 'clients.json');
        $km = $this->readJson('km', 'clients.json');
        $byId = [
            'en' => collect($en)->keyBy('id'),
            'km' => collect($km)->keyBy('id'),
        ];
        $sort = 1;
        foreach ($byId['en'] as $id => $item) {
            // Find client by English name in translations if any
            $clientId = DB::table('clients')
                ->join('client_translations as ct', 'ct.client_id', '=', 'clients.id')
                ->where('ct.locale', 'en')
                ->where('ct.name', $item['name'])
                ->value('clients.id');

            if (!$clientId) {
                $clientId = DB::table('clients')->insertGetId([
                    'name' => $item['name'] ?? 'N/A', // base column not nullable in migration
                    'image' => $item['logo'] ?? null,
                    'sort' => $sort,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('clients')->where('id', $clientId)->update([
                    'image' => $item['logo'] ?? null,
                    'sort' => $sort,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byId[$locale][$id] ?? null;
                if (!$t) continue;
                DB::table('client_translations')->updateOrInsert(
                    ['client_id' => $clientId, 'locale' => $locale],
                    [
                        'name' => $t['name'] ?? ($locale === 'en' ? $item['name'] ?? 'N/A' : ''),
                        'description' => $t['description'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $sort++;
        }
    }

    private function seedPartners(): void
    {
        $enPath = $this->dataPath . '/en/clients.json'; // No partners.json; reuse clients for demo if needed
        if (!File::exists($enPath)) {
            return; // nothing to seed
        }
        $en = json_decode(File::get($enPath), true) ?? [];
        $kmPath = $this->dataPath . '/km/clients.json';
        $km = File::exists($kmPath) ? json_decode(File::get($kmPath), true) : [];

        $byId = [
            'en' => collect($en)->keyBy('id'),
            'km' => collect($km)->keyBy('id'),
        ];

        $sort = 1;
        foreach ($byId['en'] as $id => $item) {
            $partnerId = DB::table('partners')
                ->join('partner_translations as pt', 'pt.partner_id', '=', 'partners.id')
                ->where('pt.locale', 'en')
                ->where('pt.name', $item['name'])
                ->value('partners.id');

            if (!$partnerId) {
                $partnerId = DB::table('partners')->insertGetId([
                    'logo' => $item['logo'] ?? null,
                    'website_url' => $item['website'] ?? null,
                    'sort' => $sort,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('partners')->where('id', $partnerId)->update([
                    'logo' => $item['logo'] ?? null,
                    'website_url' => $item['website'] ?? null,
                    'sort' => $sort,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byId[$locale][$id] ?? null;
                if (!$t) continue;
                DB::table('partner_translations')->updateOrInsert(
                    ['partner_id' => $partnerId, 'locale' => $locale],
                    [
                        'name' => $t['name'] ?? ($locale === 'en' ? $item['name'] ?? 'N/A' : ''),
                        'description' => $t['description'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $sort++;
        }
    }

    private function seedChoosings(): void
    {
        $en = $this->readJson('en', 'choosing.json');
        $km = $this->readJson('km', 'choosing.json');
        $byId = [
            'en' => collect($en)->keyBy('id'),
            'km' => collect($km)->keyBy('id'),
        ];
        $sort = 1;
        foreach ($byId['en'] as $id => $item) {
            $chId = DB::table('choosings')
                ->join('choosing_translations as ct', 'ct.choosing_id', '=', 'choosings.id')
                ->where('ct.locale', 'en')
                ->where('ct.title', $item['name'])
                ->value('choosings.id');

            if (!$chId) {
                $chId = DB::table('choosings')->insertGetId([
                    'image' => $item['icon'] ?? null, // No icon column; store it in image if available
                    'is_active' => true,
                    'sort' => $sort,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('choosings')->where('id', $chId)->update([
                    'image' => $item['icon'] ?? null,
                    'sort' => $sort,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byId[$locale][$id] ?? null;
                if (!$t) continue;
                DB::table('choosing_translations')->updateOrInsert(
                    ['choosing_id' => $chId, 'locale' => $locale],
                    [
                        'title' => $t['name'] ?? ($locale === 'en' ? $item['name'] ?? 'N/A' : ''),
                        'description' => $t['description'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $sort++;
        }
    }

    private function seedAchievements(): void
    {
        $en = $this->readJson('en', 'achievements.json');
        $km = $this->readJson('km', 'achievements.json');
        $byId = [
            'en' => collect($en)->keyBy('id'),
            'km' => collect($km)->keyBy('id'),
        ];
        $sort = 1;
        foreach ($byId['en'] as $id => $item) {
            $aId = DB::table('achievements')
                ->join('achievement_translations as at', 'at.achievement_id', '=', 'achievements.id')
                ->where('at.locale', 'en')
                ->where('at.title', $item['title'])
                ->value('achievements.id');

            if (!$aId) {
                $aId = DB::table('achievements')->insertGetId([
                    'icon' => $item['icon'] ?? null,
                    'value' => (int)($item['number'] ?? 0),
                    'sort' => $sort,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('achievements')->where('id', $aId)->update([
                    'icon' => $item['icon'] ?? null,
                    'value' => (int)($item['number'] ?? 0),
                    'sort' => $sort,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byId[$locale][$id] ?? null;
                if (!$t) continue;
                DB::table('achievement_translations')->updateOrInsert(
                    ['achievement_id' => $aId, 'locale' => $locale],
                    [
                        'title' => $t['title'] ?? ($locale === 'en' ? $item['title'] ?? 'N/A' : ''),
                        'subtitle' => $t['suffix'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            $sort++;
        }
    }

    private function seedCareers(): void
    {
        $en = $this->readJson('en', 'careers.json');
        $km = $this->readJson('km', 'careers.json');
        $byIdx = [
            'en' => collect($en)->values(),
            'km' => collect($km)->values(),
        ];

        // Map JSON values to DB enum values
        $typeMap = [
            'Full time' => 'full_time',
            'Part time' => 'part_time',
            'Internship' => 'internship',
        ];
        $levelMap = [
            'Junior' => 'junior',
            'Mid' => 'mid',
            'Senior' => 'senior',
            'Intern' => 'junior', // map Intern to junior
        ];
        $priorityMap = [
            'Low' => 'low',
            'Regular' => 'medium',
            'Medium' => 'medium',
            'High' => 'high',
            'Urgent' => 'high', // map Urgent to high
        ];

        foreach ($byIdx['en'] as $i => $item) {
            $slug = Str::slug($item['slug'] ?? $item['title'] ?? (string)$i);
            $slug = $this->uniqueSlug('careers', $slug);
            
            $careerId = DB::table('careers')->where('slug', $slug)->value('id');

            $type = $typeMap[$item['type'] ?? ''] ?? 'full_time';
            $level = $levelMap[$item['level'] ?? ''] ?? 'junior';
            $priority = $priorityMap[$item['priority'] ?? ''] ?? 'medium';

            if (!$careerId) {
                $careerId = DB::table('careers')->insertGetId([
                    'slug' => $slug,
                    'location' => $item['location'] ?? null,
                    'deadline' => $item['date'] ?? null,
                    'type' => $type,
                    'level' => $level,
                    'priority' => $priority,
                    'sort' => $i + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('careers')->where('id', $careerId)->update([
                    'slug' => $slug,
                    'location' => $item['location'] ?? null,
                    'deadline' => $item['date'] ?? null,
                    'type' => $type,
                    'level' => $level,
                    'priority' => $priority,
                    'updated_at' => now(),
                ]);
            }

            foreach ($this->locales() as $locale) {
                $t = $byIdx[$locale][$i] ?? null;
                if (!$t) continue;
                
                DB::table('career_translations')->updateOrInsert(
                    ['career_id' => $careerId, 'locale' => $locale],
                    [
                        'title' => $t['title'] ?? ($locale === 'en' ? $item['title'] ?? 'N/A' : ''),
                        'short_description' => $t['short_description'] ?? null,
                        'description' => $t['description'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
