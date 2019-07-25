@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center auth">
	<div class="mdc-card mdc-card--outlined">
		<form id="password-reset-form" method="POST" action="{{ route('password.update') }}" class="mdc-card__content d-flex flex-column auth-form">
			@csrf
			<input type="hidden" name="token" value="{{ $token }}">

			<div class="mdc-card__content-header">
				<h3>{{ __('Reset Password') }}</h3>
			</div>

			<label class="mdc-text-field mdc-card__action">
				<input type="email" class="mdc-text-field__input" name="email" autocomplete="email" value="{{ $email ?? old('email') }}" required autofocus>
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
				<button type="submit" class="mdc-button mdc-button--unelevated mdc-card__action mdc-card__action--button" form="password-reset-form">
					<span class="mdc-button__label">{{ __('Reset Password') }}</span>
				</button>
			</div>
		</div>
	</div>
</div>
@endsection
