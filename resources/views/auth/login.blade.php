@extends('layouts.app')

@section('title', __('Login'))

@section('content')
<div id="login" class="d-flex justify-content-center auth">
	<div id="login-card" class="mdc-card mdc-card--outlined">
		<form id="login-form" method="POST" action="{{ route('login') }}" class="mdc-card__content d-flex flex-column auth-form">
			@csrf
			<div class="mdc-card__content-header">
				<h3>{{ __('Login') }}</h3>
			</div>
			<label class="mdc-text-field mdc-card__action">
				<input type="email" class="mdc-text-field__input" name="email" autocomplete="email" autofocus>
				<span class="mdc-floating-label">{{ __('E-Mail Address') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>

			@error('email')
			<div class="mdc-text-field-helper-line">
				<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
			</div>
			@enderror

			<label class="mdc-text-field mdc-card__action">
				<input type="password" class="mdc-text-field__input" name="password" autocomplete="current-password">
				<span class="mdc-floating-label">{{ __('Password') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>

			@error('password')
			<div class="mdc-text-field-helper-line">
				<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
			</div>
			@enderror

			<div class="mdc-form-field mdc-card__action">
				<div class="mdc-checkbox">
					<input class="mdc-checkbox__native-control" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
					<div class="mdc-checkbox__background">
						<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
							<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59" />
						</svg>
						<div class="mdc-checkbox__mixedmark"></div>
					</div>
				</div>
				<label for="remember">{{ __('Remember Me') }}</label>
			</div>
		</form>
		<div class="mdc-card__actions justify-content-end">
			<div class="mdc-card__action-buttons flex-wrap justify-content-end">
				<a id="register-button" class="mdc-button mdc-card__action mdc-card__action--button" href="{{ route('register') }}">
					<span class="mdc-button__label">{{ __('Register') }}</span>
				</a>
				@if (Route::has('password.request'))
				<a id="forgot-password-button" class="mdc-button mdc-card__action mdc-card__action--button" href="{{ route('password.request') }}">
					<span class="mdc-button__label">{{ __('Forgot Your Password?') }}</span>
				</a>
				@endif
				<button type="submit" class="mdc-button mdc-button--unelevated mdc-card__action mdc-card__action--button" form="login-form">
					<span class="mdc-button__label">{{ __('Login') }}</span>
				</button>
			</div>
		</div>
	</div>
</div>
@endsection
