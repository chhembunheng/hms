@props(['pricing' => [], 'title' => '', 'subtitle' => '', 'planning' => ['starter' => 100, 'standard' => 200, 'premium' => 300, 'enterprise' => 400]])
@if ($pricing)
    <section class="overview-section bg-grey section-padding">
        <div class="container">
            <div class="row align-items-center">
                @if ($title || $subtitle)
                    <div class="col-md-12">
                        <div class="section-title">
                            @if ($title)
                                <h2>{{ $title }}</h2>
                            @endif
                            @if ($subtitle)
                                <p>{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    <table style="width: 100%; border-collapse: collapse; border: none">
                        <thead>
                            <tr>
                                <th class="text-center pb-4" colspan="5">
                                    <div class="btn-group mb-4">
                                        <button type="button" class="btn btn-light active">Monthly</button>
                                        <button type="button" class="btn btn-light">Yearly <sup><span class="badge badge-danger">10% off</span></sup></button>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-left px-2">
                                    <p class="m-0 p-2"><strong>Features</strong></p>
                                </th>
                                @foreach ($planning as $item => $price)
                                    <th class="text-center">
                                        <p class="m-0"><strong>{{ ucfirst($item) }}</strong></p>
                                        <small><strong class="m-0">${{ $price }}/month</strong></small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pricing as $feature)
                                <tr>
                                    <td class="text-left px-2">
                                        <p class="m-0 p-2" style="border-top: 1px #dfe4e8 dashed;">
                                            {{ $feature->title }}
                                        </p>
                                    </td>
                                    @foreach ($planning as $plan => $price)
                                        <td class="text-center px-2">
                                            <p class="m-0 p-2" style="border-top: 1px #dfe4e8 dashed;">
                                                @if ($feature->{$plan} ?? false)
                                                    <i class="fa-solid fa-check-circle text-success fa-fw fa-lg"></i>
                                                @else
                                                    <i class="fa-solid fa-circle-xmark text-danger fa-fw fa-lg"></i>
                                                @endif
                                            </p>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endif
