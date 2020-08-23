@extends('layouts.app')

@section('title', __('edit account'))

@section('content')
<div class="d-flex justify-content-center auth">
	<div class="mdc-card mdc-card--outlined">
		<form id="edit-form" method="POST" action="{{ route('account.update') }}" class="mdc-card__content d-flex flex-column auth-form">
			@method('PATCH')
			@csrf
			<div class="mdc-card__content-header">
				<h3>{{ __('edit account') }}</h3>
			</div>

			<label class="mdc-text-field mdc-card__action">
				<input type="text" class="mdc-text-field__input" name="username" autocomplete="username" value="{{ $user->username }}" required>
				<span class="mdc-floating-label">{{ __('Username') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>
			@error('username')
			<div class="mdc-text-field-helper-line">
				<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
			</div>
			@enderror

			<label class="mdc-text-field mdc-card__action">
				<input type="email" class="mdc-text-field__input" name="email" autocomplete="email" value="{{ $user->email }}" required>
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
				<button type="button" class="mdc-button mdc-button--outlined ml-2" form="edit-form" onclick="axios_submit(this)">
					<span class="mdc-button__label">{{ __('update') }}</span>
				</button>
			</div>
		</div>
	</div>
</div>
@endsection
