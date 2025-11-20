<script type="text/template" id="detailTemplate">
{!! str_replace("\n", "", view('frontends.products.partials.detail', [
    'featureIndex' => '__FEATURE_INDEX__',
    'detailIndex' => '__DETAIL_INDEX__',
    'detail' => ['translations' => []],
    'locales' => $locales
])->render()) !!}
</script>
