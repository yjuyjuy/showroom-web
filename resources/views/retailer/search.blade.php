@extends('layouts.app')
@section('title', __('Search retailer'))

@section('content')
<form>
	<div class="d-flex w-100 justify-content-center align-items-center">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" name="name" class="mdc-text-field__input" aria-label="Label" autofocus
			placeholder="@if($not_found){{ __('retailer not found') }}@else{{ __('Search retailer') }}@endif">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<button type="submit" class="mdc-icon-button material-icons ml-2">
			<i class="material-icons">search</i>
		</button>
	</div>
</form>
@endsection