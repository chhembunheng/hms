<script type="text/template" id="featureTemplate">
{!! str_replace("\n", "", view('frontends.products.partials.feature', [
    'index' => '__FEATURE_INDEX__',
    'feature' => ['translations' => [], 'details' => []],
    'locales' => $locales
])->render()) !!}
</script>
