<x-frontend-layout>
    @isset($stats['user_count'])
        <div class="px-4 py-2 text-sm text-gray-600" data-test="user-count">{{ $stats['user_count'] }}</div>
    @endisset
    @php
        $feedbacks = $feedbacks ?? collect();
        if (isset($client)) {
            $dir = public_path('site/assets/img/clients');
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            $files = scandir($dir);
            $clients = [];
            foreach ($files as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $allowed_ext)) {
                    $clients[] = 'site/assets/img/clients/' . $file;
                }
            }
        }
    @endphp
    @isset($sliders)
        @include('sites.sections.sliders', ['sliders' => $sliders])
    @endisset
    @isset($area)
        @include('sites.sections.title-area', ['title' => $area['title'], 'subtitle' => $area['subtitle']])
    @endisset
    @isset($clients)
        @include('sites.sections.clients', [
            'clients' => $clients,
            'title' => __('global.our_clients'),
            'subtitle' => __('global.we_have_worked_with_amazing_clients'),
        ])
    @endisset
    @if (isset($articles) && !isset($article))
        @include('sites.sections.articles', ['articles' => $articles, 'title' => __('global.article'), 'subtitle' => __('global.latest_post_from_article')])
    @endif
    @isset($article)
        @php
            $slug = $article->slug;
            $articles_list = $articles ?? collect();
            $currentSlug = $slug;
            $index = $articles_list->search(fn($a) => $a->slug === $currentSlug);

            $prev = $index !== false && $index > 0 ? $articles_list[$index - 1] : null;
            $next = $index !== false && $index < $articles_list->count() - 1 ? $articles_list[$index + 1] : null;
            $next = !empty($next) ? route('blogs', ['locale' => app()->getLocale(), 'slug' => $next->slug]) : '';
            $prev = !empty($prev) ? route('blogs', ['locale' => app()->getLocale(), 'slug' => $prev->slug]) : '';
        @endphp
        @include('sites.sections.article', ['articles' => $articles, 'article' => $article, 'title' => __('global.article'), 'subtitle' => __('global.latest_post_from_article'), 'next' => $next, 'prev' => $prev])
    @endisset
    @isset($products)
        @include('sites.sections.products', ['products' => $products, 'title' => __('global.products'), 'subtitle' => __('global.what_we_offer')])
    @endisset
    @isset($details)
        @foreach ($details as $i => $box)
            @include('sites.sections.box', [
                'box' => $box,
                'details' => $box->details ?? [],
                'left' => $i % 2 == 0 ? false : true,
                'title' => $box->title ?? '',
                'subtitle' => $box->description ?? '',
            ])
        @endforeach
    @endisset
    @isset($pricing)
        @include('sites.sections.pricing', ['pricing' => $pricing, 'title' => __('global.pricing'), 'subtitle' => __('global.our_pricing_plans')])
    @endisset
    @isset($service)
        @include('sites.sections.service', ['services' => $services, 'service' => $service, 'title' => $area['title'], 'subtitle' => $area['subtitle']])
    @endisset
    @if (isset($services) && !isset($service))
        @include('sites.sections.services', ['services' => $services, 'title' => __('global.our_services'), 'subtitle' => __('global.what_we_offer')])
    @endif
    @isset($achievements)
        @include('sites.sections.achievements', ['achievements' => $achievements, 'title' => __('global.our_achievements'), 'subtitle' => __('global.what_we_have_accomplished')])
    @endisset
    @isset($teams)
        @include('sites.sections.teams', ['teams' => $teams, 'title' => __('global.our_teams'), 'subtitle' => __('global.meet_our_experts')])
    @endisset
    @if(isset($integrations) && !isset($integration))
        @include('sites.sections.integrations', ['integrations' => $integrations, 'title' => __('global.our_integrations'), 'subtitle' => __('global.connect_your_favorite_tools')])
    @endif
    @isset($integration)
        @include('sites.sections.integration', ['integration' => $integration, 'title' => $area['title'], 'subtitle' => $area['subtitle']])
    @endisset
    @isset($careers)
        @include('sites.sections.career', ['careers' => $careers, 'title' => __('global.our_careers'), 'subtitle' => __('global.join_our_team')])
    @endisset
    @isset($categories)
        @include('sites.sections.faqs', ['categories' => $categories])
    @endisset
    @isset($content)
        @include('sites.sections.content', ['content' => $content])
    @endisset
    @isset($contacts)
        @include('sites.sections.contact', ['contacts' => $contacts, 'title' => __('global.get_in_touch'), 'subtitle' => __('global.contact_us')])
    @endisset
    @isset($choosing)
        @include('sites.sections.choosing', ['choosing' => $choosing, 'title' => __('global.why_choose_us'), 'subtitle' => __('global.best_reason_to_choose_us')])
    @endisset
    @isset($talk)
        @include('sites.sections.talk', ['title' => __('global.let_s_talk'), 'subtitle' => __('global.get_in_touch_with_us')])
    @endisset
</x-frontend-layout>
