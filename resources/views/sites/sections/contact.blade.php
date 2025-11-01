@props(['contacts' => []])
@if ($contacts)
    <section class="contact-info-wrapper bg-grey">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>{{ __('global.contact_info') }}</h2>
                    </div>
                </div>
                @foreach ($contacts as $contact)
                    <div class="col-lg-4 col-md-6">
                        <div class="contact-info-content">
                            <h5><i class="{{ $contact->icon }}"></i> &nbsp;{{ $contact->title }}</h5>
                            <p>{{ $contact->description }}</p>
                            <a href="tel:{{ $contact->phone }}"><i class="fa-solid fa-phone"></i>&nbsp;{{ $contact->phone }}</a>
                            <a href="mailto:{{ $contact->email }}"><i class="fa-solid fa-envelope"></i>&nbsp;{{ $contact->email }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif