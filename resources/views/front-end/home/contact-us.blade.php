@extends('front-end.layouts.app')
@section('title', 'Contact Us')
@section('content')
<div class="py-4 border-bottom bg-light">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-2 small">
				<li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
				<li class="breadcrumb-item active" aria-current="page">Contact Us</li>
			</ol>
		</nav>
	<h1 class="h3 mb-0">Get In Touch</h1>
	<p class="text-muted mt-2 mb-0">{{ \App\Models\Setting::get('site_tagline', "We'd love to hear from you. Send us a message and our team will respond soon.") }}</p>
	</div>
</div>

<section class="py-5">
	<div class="container">
		@if(session('contact_submitted'))
			@if(session('contact_mail_sent'))
				<div class="alert alert-success" role="alert">
					Thank you! Your message has been received. We'll reply soon.
				</div>
			@else
				<div class="alert alert-warning" role="alert">
					Your message was saved, but we could not send notification email at this time. We will still review it shortly.
				</div>
			@endif
		@endif
		<div class="row g-4">
			<div class="col-lg-7">
				<div class="card shadow-sm">
					<div class="card-body p-4">
						<h2 class="h5 mb-3">Send a Message</h2>
						<form method="POST" action="{{ route('home.contact.submit') }}" novalidate>
							@csrf
							<div class="mb-3">
								<label for="contact_name" class="form-label">Name <span class="text-danger">*</span></label>
								<input type="text" name="name" id="contact_name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
								@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<div class="mb-3">
								<label for="contact_email" class="form-label">Email <span class="text-danger">*</span></label>
								<input type="email" name="email" id="contact_email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
								@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<div class="mb-3">
								<label for="contact_phone" class="form-label">Phone</label>
								<input type="text" name="phone" id="contact_phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Optional">
								@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<div class="mb-3">
								<label for="contact_subject" class="form-label">Subject <span class="text-danger">*</span></label>
								<input type="text" name="subject" id="contact_subject" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror" required>
								@error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<div class="mb-3">
								<label for="contact_message" class="form-label">Message <span class="text-danger">*</span></label>
								<textarea name="message" id="contact_message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
								@error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<div class="d-flex align-items-center gap-3">
								<button type="submit" class="btn btn-primary">Send Message</button>
								<small class="text-muted">We reply within 24 hours.</small>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="mb-4">
					<h2 class="h5">Store Information</h2>
					<p class="mb-2"><i class="ri-map-pin-line text-primary me-1"></i> {{ \App\Models\Setting::get('contact_address', 'Shop No-95, Ground Floor, Shimanto Shambar Shopping Mall, Dhaka-1205.') }}</p>
					<p class="mb-2"><i class="ri-phone-line text-primary me-1"></i> {{ \App\Models\Setting::get('contact_phone', '+88 01648-022175') }}</p>
					<p class="mb-2"><i class="ri-mail-line text-primary me-1"></i> {{ \App\Models\Setting::get('contact_email', 'sajidbeautybd@gmail.com') }}</p>
					<div class="mt-3">
						<h3 class="h6 mb-2">Store Hours</h3>
						<ul class="list-unstyled small mb-0">
							<li class="d-flex justify-content-between"><span>Sunday</span><span>{{ \App\Models\Setting::get('hours_sunday', '10:30 AM – 8:30 PM') }}</span></li>
							<li class="d-flex justify-content-between"><span>Monday</span><span>{{ \App\Models\Setting::get('hours_monday', '10:30 AM – 8:30 PM') }}</span></li>
							<li style="color: red;" class="d-flex justify-content-between"><span>Tuesday</span><span>{{ \App\Models\Setting::get('hours_tuesday', 'Closed') }}</span></li>
							<li class="d-flex justify-content-between"><span>Wednesday</span><span>{{ \App\Models\Setting::get('hours_wednesday', '10:30 AM – 8:30 PM') }}</span></li>
							<li class="d-flex justify-content-between"><span>Thursday</span><span>{{ \App\Models\Setting::get('hours_thursday', '10:30 AM – 8:30 PM') }}</span></li>
							<li class="d-flex justify-content-between"><span>Friday</span><span>{{ \App\Models\Setting::get('hours_friday', '10:30 AM – 9:00 PM') }}</span></li>
							<li class="d-flex justify-content-between"><span>Saturday</span><span>{{ \App\Models\Setting::get('hours_saturday', '10:30 AM – 9:00 PM') }}</span></li>
						</ul>
					</div>
				</div>
				<div class="mb-4">
					<h2 class="h6 text-uppercase fw-bold">Follow Us</h2>
					<div class="d-flex gap-2">
						<a href="{{ \App\Models\Setting::get('facebook_url', 'https://facebook.com/') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="ri-facebook-fill"></i></a>
						<a href="{{ \App\Models\Setting::get('instagram_url', 'https://instagram.com/') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="ri-instagram-fill"></i></a>
						<a href="{{ \App\Models\Setting::get('twitter_url', 'https://twitter.com/') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="ri-twitter-fill"></i></a>
						<a href="{{ \App\Models\Setting::get('youtube_url', 'https://youtube.com/') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="ri-youtube-fill"></i></a>
					</div>
				</div>
				@php($map = \App\Models\Setting::get('map_iframe_url'))
				<div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
					<iframe title="Google Map" src="{{ $map ?: 'https://www.google.com/maps?q=Shimanto+Shambhar+Shopping+Mall+Dhaka&output=embed' }}" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection