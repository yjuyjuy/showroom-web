@extends('layouts.app')

@section('title', __('Verify Your Email Address'))

@section('content')
<div class="d-flex justify-content-center mt-5 mx-3">
	<div class="d-flex flex-column">
		<div class="my-4 mdc-typography--headline5">{{ __('Verify Your Email Address') }}</div>
		<div class="mdc-typography--headline6">
			{{ __('Before proceeding, please check your email for a verification link.') }}
			{{ __('If you did not receive the email') }},
			<form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
		    @csrf
		    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
	        {{ __('click here to request another') }}
		    </button>.
			</form>
		</div>
	</div>
</div>
	@if (session('resent'))
	<div class="mdc-snackbar">
		<div class="mdc-snackbar__surface">
			<div class="mdc-snackbar__label"
					 role="status"
					 aria-live="polite">
				{{ __('A fresh verification link has been sent to your email address.') }}
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
@endsection
