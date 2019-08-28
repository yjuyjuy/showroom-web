@extends('layouts.app')

@section('title', '功能建议')

@section('content')
<div class="fullscreen-center">
	<form action="{{ route('suggestion.store') }}" class="d-flex flex-column mt-n5" method="post">
		@csrf
		<div class="my-3">
			<span class="mdc-typography--headline5">功能建议</span>
		</div>
		<div class="my-3">
			<div class="mdc-text-field mdc-text-field--textarea mdc-text-field--no-label mdc-text-field--fullwidth">
			  <textarea class="mdc-text-field__input" rows="6" cols="40" name="content"></textarea>
			  <div class="mdc-notched-outline">
			    <div class="mdc-notched-outline__leading"></div>
			    <div class="mdc-notched-outline__trailing"></div>
			  </div>
			</div>
		</div>
		<div class="text-right my-3">
			<button type="button" class="mdc-button" onclick="window.history.back()">
				<span class="mdc-button__label">{{ __('Back') }}</span>
			</a>
			<button type="submit" class="mdc-button">
				<span class="mdc-button__label">{{ __('submit') }}</span>
			</button>
		</div>
	</form>
</div>
@endsection
