@extends('layouts.app')

@section('title', __('account status'))

@section('content')
@if(!$user->type)
<div class="d-flex justify-content-center auth">
	<div class="mdc-card mdc-card--outlined">
		<form id="request-form" method="POST" action="{{ route('account.request') }}" class="mdc-card__content d-flex flex-column auth-form">
			@csrf
			<div class="mdc-card__content-header">
				<h3>{{ __('Request for an account upgrade') }}</h3>
			</div>

			<label class="mdc-text-field mdc-card__action">
				<input type="text" class="mdc-text-field__input" name="wechat_id" autocomplete="off" placeholder="">
				<span class="mdc-floating-label">{{ __('Wechat id') }}</span>
				<div class="mdc-line-ripple"></div>
			</label>

			@error('wechat_id')
			<div class="mdc-text-field-helper-line">
				<div class="mdc-text-field-helper-text mdc-text-field-helper-text--persistent">{{ $message }}</div>
			</div>
			@enderror
		</form>
		<div class="mdc-card__actions justify-content-end">
			<div class="mdc-card__action-buttons flex-wrap justify-content-end">
				<button type="submit" class="mdc-button mdc-button--unelevated mdc-card__action mdc-card__action--button" form="request-form">
					<span class="mdc-button__label">{{ __('submit') }}</span>
				</button>
			</div>
		</div>
	</div>
</div>
@else
<div class="fullscreen-center">
	<span class="mdc-typography--headline6 mx-5 text-center mt-n5">
		@if($user->is_reseller)
		{{ __('Your account is already upgraded. Please contact our representative if you need further upgrade') }}
		@elseif($user->is_rejected)
		{{ __('Sorry, your account does not qualify for an upgrade') }}
		@elseif($user->is_pending)
		{{ __('We are reviewing your request, our representative will contact you during the process') }}
		@endif
	</span>
</div>
@endif
@endsection
