@extends('layouts.app')

@section('title', __('Reset Password'))

@section('content')
<div class="d-flex justify-content-center auth">
	<div class="mdc-card mdc-card--outlined">

		<form id="password-email-form" method="POST" action="{{ route('password.email') }}" class="mdc-card__content d-flex flex-column auth-form">
			@csrf
			<div class="mdc-card__content-header">
				<h3>{{ __('Reset Password') }}</h3>
			</div>

			<label class="mdc-text-field mdc-card__action">
				<input type="email" class="mdc-text-field__input" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
				<span class="mdc-floating-label">{{ __('E-Mail Address') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>

			@error('email')
			<div class="mdc-text-field-helper-line">
				<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
			</div>
			@enderror

		</form>
		<div class="mdc-card__actions justify-content-end">
			<div class="mdc-card__action-buttons flex-wrap justify-content-end">
				<button type="submit" class="mdc-button mdc-button--unelevated mdc-card__action mdc-card__action--button" form="password-email-form">
					<span class="mdc-button__label">{{ __('Send Password Reset Link') }}</span>
				</button>
			</div>
		</div>

		@if (session('status'))
		<div class="mdc-snackbar">
			<div class="mdc-snackbar__surface">
				<div class="mdc-snackbar__label"
						 role="status"
						 aria-live="polite">
					{{ session('status') }}
				</div>
				<div class="mdc-snackbar__actions">
					<button type="button" class="mdc-button mdc-button--outlined mdc-snackbar__action"
									onclick="window.snackbar.close();">
						<span class="mdc-button__label">OK</span>
					</button>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
@endsection
