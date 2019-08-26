@extends('layouts.app')

@section('title', __('Following retailers'))

@section('content')
<div class="d-flex flex-column">

	<form class="my-5 d-flex justify-content-center align-items-center">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
			<input type="text" name="name" class="mdc-text-field__input" aria-label="Label" autofocus
			placeholder="{{ __('Search retailer') }}">
			<div class="mdc-notched-outline">
				<div class="mdc-notched-outline__leading"></div>
				<div class="mdc-notched-outline__trailing"></div>
			</div>
		</div>
		<button type="submit" class="mdc-icon-button material-icons ml-2">
			<i class="material-icons">search</i>
		</button>
	</form>

	@if($not_found)
		<div class="my-5 text-center">{{ __('retailer not found') }}"{{ old('name') }}"</div>
	@endif

	<div class="my-3 d-flex justify-content-center flex-wrap">
		@if($retailers->isNotEmpty())
			<span class="mdc-typography--headline6 my-1 mx-3">已关注: </span>
			@foreach($retailers->shuffle() as $retailer)
				<a href="{{ route('retailer.products.index', ['retailer' => $retailer,]) }}" class="mdc-typography--headline6 my-1 mx-3">{{ $retailer->name }}</a>
			@endforeach
		@else
			<span>没有关注的卖家</span>
		@endif
	</div>
</div>
@endsection
