@extends('layouts.app')

@section('title', __('guests.stay_history_for') . ' ' . $guest->full_name)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{ __('global.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('guests.list.index') }}">{{ __('guests.guests') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('guests.list.show', $guest->id) }}">{{ $guest->full_name }}</a></li>
            <li class="breadcrumb-item active">{{ __('guests.stay_history') }}</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('guests.stay_history_for') }} {{ $guest->full_name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('guests.list.show', $guest->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>{{ __('global.back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($guest->checkIns->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('checkins.check_in_date') }}</th>
                                    <th>{{ __('checkins.check_out_date') }}</th>
                                    <th>{{ __('rooms.room') }}</th>
                                    <th>{{ __('rooms.room_type') }}</th>
                                    <th>{{ __('rooms.floor') }}</th>
                                    <th>{{ __('checkins.total_amount') }}</th>
                                    <th>{{ __('checkins.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guest->checkIns as $checkIn)
                                    <tr>
                                        <td>{{ $checkIn->check_in_date ? $checkIn->check_in_date->format('d-m-Y') : '-' }}</td>
                                        <td>{{ $checkIn->actual_check_out_at ? $checkIn->actual_check_out_at->format('d-m-Y') : ($checkIn->expected_check_out_date ? $checkIn->expected_check_out_date->format('d-m-Y') : '-') }}</td>
                                        <td>{{ $checkIn->room ? $checkIn->room->room_number : '-' }}</td>
                                        <td>{{ $checkIn->room && $checkIn->room->roomType ? $checkIn->room->roomType->localized_name : '-' }}</td>
                                        <td>{{ $checkIn->room && $checkIn->room->floor ? $checkIn->room->floor->localized_name : '-' }}</td>
                                        <td>${{ number_format($checkIn->paid_amount, 2) }}</td>
                                        <td>
                                            @if($checkIn->status === 'checked_in')
                                                <span class="badge bg-success">{{ __('checkins.checked_in') }}</span>
                                            @elseif($checkIn->status === 'checked_out')
                                                <span class="badge bg-secondary">{{ __('checkins.checked_out') }}</span>
                                            @elseif($checkIn->status === 'cancelled')
                                                <span class="badge bg-danger">{{ __('checkins.cancelled') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ __('checkins.pending') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('global.no_data') }}</h5>
                        <p class="text-muted">{{ __('guests.no_stay_history') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
