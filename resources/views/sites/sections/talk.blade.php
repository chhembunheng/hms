@props(['title' => '', 'subtitle' => ''])
<div class="contact-section bg-grey section-padding">
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
            <div class="col-lg-10 offset-lg-1">
                <div class="contact-form">
                    <div class="form-messages"></div>
                    <form id="contact-form" class="contact-form form" action="{{ route('submit-contact', ['locale' => app()->getLocale()]) }}" method="POST" error-message="{{ __('global.contact_form_error_message') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group form-group-icon">
                                    <input type="text" name="name" id="name" class="form-control" required placeholder="{{ __('global.your_name') }}">
                                    <i class="fa-jelly fa-solid fa-user"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group form-group-icon">
                                    <input type="email" name="email" id="email" class="form-control" required placeholder="{{ __('global.your_email') }}">
                                    <i class="fa-jelly fa-solid fa-envelope"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group form-group-icon">
                                    <input type="text" name="phone" id="phone" required class="form-control" placeholder="{{ __('global.your_phone') }}">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group form-group-icon">
                                    <input type="text" name="subject" id="subject" class="form-control" required placeholder="{{ __('global.your_subject') }}">
                                    <i class="fa-solid fa-comment"></i>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <textarea name="message" class="form-control" id="message" cols="30" rows="6" required placeholder="{{ __('global.your_message') }}"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <button type="submit" class="default-btn submit-btn"><i class="fa-jelly fa-solid fa-paper-plane"></i> &nbsp;{{ __('global.send_message') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
