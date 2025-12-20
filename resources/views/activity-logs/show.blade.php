<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('global.activity_log_details') }}</h3>
                        <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>{{ __('global.activity_log_details') }}</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>{{ __('global.user') }}</th>
                                        <td>{{ $activityLog->user ? $activityLog->user->name : 'System' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('global.action') }}</th>
                                        <td>{{ ucfirst($activityLog->action) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('global.model') }}</th>
                                        <td>{{ class_basename($activityLog->model_type) }} #{{ $activityLog->model_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('global.ip_address') }}</th>
                                        <td>{{ $activityLog->ip_address }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('global.user_agent') }}</th>
                                        <td>{{ $activityLog->user_agent }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('global.created_at') }}</th>
                                        <td>{{ $activityLog->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>{{ __('global.changes') }}</h5>
                                @if($activityLog->old_values)
                                <h6>{{ __('global.old_values') }}</h6>
                                <pre>{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                                @if($activityLog->new_values)
                                <h6>{{ __('global.new_values') }}</h6>
                                <pre>{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
