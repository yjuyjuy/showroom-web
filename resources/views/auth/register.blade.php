@extends('layouts.app')

@section('title', __('Register'))

@section('content')
<div id="register" class="d-flex justify-content-center auth">
	<div id="register-card" class="mdc-card mdc-card--outlined">
		<form id="register-form" method="POST" action="{{ route('register') }}" class="mdc-card__content d-flex flex-column auth-form">
			@csrf
			<div class="mdc-card__content-header">
				<h3>{{ __('Register') }}</h3>
			</div>

			<label class="mdc-text-field mdc-card__action">
				<input type="text" class="mdc-text-field__input" name="username" autocomplete="username" value="{{ old('username') }}" required>
				<span class="mdc-floating-label">{{ __('Username') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>
			@error('username')
			<div class="mdc-text-field-helper-line">
				<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
			</div>
			@enderror

			<label class="mdc-text-field mdc-card__action">
				<input type="email" class="mdc-text-field__input" name="email" autocomplete="email" value="{{ old('email') }}" required>
				<span class="mdc-floating-label">{{ __('E-Mail Address') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>

			@error('email')
			<div class="mdc-text-field-helper-line">
				<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
			</div>
			@enderror

			<label class="mdc-text-field mdc-card__action">
				<input type="password" class="mdc-text-field__input" name="password" autocomplete="new-password" required>
				<span class="mdc-floating-label">{{ __('Password') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>

			@error('password')
			<div class="mdc-text-field-helper-line">
				<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
			</div>
			@enderror

			<label class="mdc-text-field mdc-card__action">
				<input type="password" class="mdc-text-field__input" name="password_confirmation" autocomplete="new-password" required>
				<span class="mdc-floating-label">{{ __('Confirm Password') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>
		</form>
		<div class="mdc-card__actions justify-content-end">
			<div class="mdc-card__action-buttons flex-wrap justify-content-end">
				<a id="login-button" class="mdc-button mdc-card__action mdc-card__action--button" href="{{ route('login') }}" tabindex="-1">
					<span class="mdc-button__label">{{ __('Login') }}</span>
				</a>
				<button type="submit" class="mdc-button mdc-button--unelevated mdc-card__action mdc-card__action--button" form="register-form">
					<span class="mdc-button__label">{{ __('Register') }}</span>
				</button>
			</div>
		</div>
	</div>
</div>
@endsection
