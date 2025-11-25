@props(['career' => null, 'title' => '', 'subtitle' => ''])
@php
    $locale = app()->getLocale();
@endphp

<section class="career-detail-section py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Header Section -->
                <div class="career-header mb-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h1 class="display-5 fw-bold mb-3 text-dark">{{ $career?->getTitle($locale) ?? '' }}</h1>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if ($career?->is_active)
                                    {!! badge($career->priority, 'priority') !!}
                                    {!! badge($career->type, 'type') !!}
                                    {!! badge($career->level, 'level') !!}
                                @else
                                    <span class="badge bg-danger">{{ __('global.closed') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Career Meta Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="meta-card p-3 border rounded-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-map-marker-alt fa-2x text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('global.location') }}</small>
                                        <strong>{{ $career?->location ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="meta-card p-3 border rounded-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-briefcase fa-2x text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('global.job_type') }}</small>
                                        <strong>{!! badge($career?->type ?? '') !!}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="meta-card p-3 border rounded-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-hourglass-end fa-2x text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">{{ __('global.deadline') }}</small>
                                        <strong>{{ $career?->deadline?->format('M d, Y') ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Posted Information -->
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span>
                            <i class="fa-solid fa-calendar me-2"></i>
                            {{ __('global.posted') }}: {{ $career?->created_at?->format('M d, Y') ?? 'N/A' }}
                        </span>
                        <span>
                            <i class="fa-solid fa-eye me-2"></i>
                            {{ __('global.level') }}: <strong
                                class="text-dark">{{ ucfirst($career?->level ?? '') }}</strong>
                        </span>
                    </div>
                </div>

                <!-- Job Description Section -->
                <div class="job-description mb-5">
                    <h3 class="h4 fw-bold mb-3 text-dark">{{ __('global.about_position') }}</h3>
                    <div class="content-text leading-relaxed text-justify">
                        {!! $career?->getShortDescription($locale) ?? '<p>No description available.</p>' !!}
                    </div>
                </div>

                <!-- Full Description -->
                @if ($career?->getDescription($locale))
                    <div class="job-details mb-5">
                        <h3 class="h4 fw-bold mb-3 text-dark">{{ __('global.job_details') }}</h3>
                        <div class="content-text leading-relaxed">
                            {!! $career?->getDescription($locale) !!}
                        </div>
                    </div>
                @endif

                <!-- Application Section -->
                <div class="application-section bg-light p-5 rounded-3">
                    <h3 class="h4 fw-bold mb-3 text-dark">{{ __('global.interested_apply') }}</h3>
                    @if ($career?->is_active)
                        <p class="text-muted mb-4">{{ __('global.apply_now_message') }}</p>
                        <button type="button" class="btn btn-primary btn-lg rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#applyCareerModal">
                            <i class="fa-solid fa-paper-plane me-2"></i>{{ __('global.apply_now') }}
                        </button>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fa-solid fa-info-circle me-2"></i>{{ __('global.position_closed') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Info Card -->
                <div class="card mb-4 shadow-sm rounded-3 border-0">
                    <div class="card-header bg-primary text-white rounded-top-3">
                        <h5 class="mb-0">{{ __('global.quick_info') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-info-item d-flex align-items-start mb-3">
                            <i class="fa-solid fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>{{ __('global.experience_level') }}</strong>
                                <p class="text-muted small mb-0">{{ ucfirst($career?->level ?? '') }}</p>
                            </div>
                        </div>
                        <div class="quick-info-item d-flex align-items-start mb-3">
                            <i class="fa-solid fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>{{ __('global.employment_type') }}</strong>
                                <p class="text-muted small mb-0">
                                    {{ ucfirst(str_replace('_', ' ', $career?->type ?? '')) }}</p>
                            </div>
                        </div>
                        <div class="quick-info-item d-flex align-items-start mb-3">
                            <i class="fa-solid fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>{{ __('global.location') }}</strong>
                                <p class="text-muted small mb-0">{{ $career?->location ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="quick-info-item d-flex align-items-start">
                            <i class="fa-solid fa-calendar-alt text-info me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>{{ __('global.deadline') }}</strong>
                                <p class="text-muted small mb-0">{{ $career?->deadline?->format('M d, Y') ?? 'Open' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Section -->
                <div class="card shadow-sm rounded-3 border-0 mb-4">
                    <div class="card-body">
                        <x-share :url="url()->current()" :title="$career?->getTitle($locale) ?? ''" :label="__('global.share_this_job')" />
                    </div>
                </div>

                <!-- CTA Card -->
                <div class="card bg-gradient shadow-sm rounded-3 border-0"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-center p-4">
                        <i class="fa-solid fa-rocket fa-3x mb-3"></i>
                        <h5 class="mb-2">{{ __('global.ready_to_join') }}</h5>
                        <p class="small mb-3">{{ __('global.apply_today_message') }}</p>
                        @if ($career?->is_active)
                            <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#applyCareerModal">
                                {{ __('global.apply_now') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Apply Modal -->
<div class="modal fade" id="applyCareerModal" tabindex="-1" aria-labelledby="applyCareerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title" id="applyCareerModalLabel">{{ __('global.apply_for_position') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="applyCareerForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="fullName" class="form-label">{{ __('global.full_name') }}</label>
                        <input type="text" class="form-control rounded-2" id="fullName" name="full_name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('global.email') }}</label>
                        <input type="email" class="form-control rounded-2" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('global.phone') }}</label>
                        <input type="tel" class="form-control rounded-2" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="resume" class="form-label">{{ __('global.upload_resume') }}</label>
                        <input type="file" class="form-control rounded-2" id="resume" name="resume"
                            accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">PDF, DOC, or DOCX (Max 5MB)</small>
                    </div>
                    <div class="mb-3">
                        <label for="coverLetter" class="form-label">{{ __('global.cover_letter') }}</label>
                        <textarea class="form-control rounded-2" id="coverLetter" name="cover_letter" rows="4"
                            placeholder="{{ __('global.tell_us_about_yourself') }}"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        {{ __('global.submit_application') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .career-detail-section {
        background: #f8f9fa;
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .career-header {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .meta-card {
        transition: all 0.3s ease;
    }

    .meta-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .content-text {
        color: #555;
        font-size: 1.05rem;
        line-height: 1.8;
    }

    .content-text p {
        margin-bottom: 1rem;
    }

    .content-text ul,
    .content-text ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }

    .content-text li {
        margin-bottom: 0.5rem;
    }

    .job-description,
    .job-details {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .application-section {
        border: 2px dashed #667eea;
    }

    .quick-info-item {
        padding: 0.75rem 0;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    @media (max-width: 768px) {
        .career-header {
            padding: 1.5rem;
        }

        .job-description,
        .job-details {
            padding: 1.5rem;
        }

        .display-5 {
            font-size: 1.75rem !important;
        }
    }
</style>
