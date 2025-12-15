<x-app-layout>
    <div class="content">
        <!-- DataTable -->
        <x-datatables title="{{ __('global.list') }}" :data="$dataTable">
        </x-datatables>
    </div>
</x-app-layout>
