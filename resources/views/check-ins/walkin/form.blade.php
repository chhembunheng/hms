<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">

            <div class="row g-3" id="pos-interface">

                <div class="col-lg-8">
                    @include('checkin.partials.room-pos-grid')
                </div>

                <div class="col-lg-4">
                    @include('checkin.partials.checkin-pos-panel')
                </div>

            </div>

        </div>
    </x-form.layout>
</x-app-layout>
