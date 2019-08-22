@extends('layouts.app')

@section('title', __('Followed retailers'))

@section('content')
<div class="m-2 d-flex flex-column">
	<div class="text-center my-4 d-flex align-items-center justify-content-center">
		<div class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label optional-form-field">
		  <input type="text" class="mdc-text-field__input" aria-label="Label" placeholder="搜索卖家">
		  <div class="mdc-notched-outline">
		    <div class="mdc-notched-outline__leading"></div>
		    <div class="mdc-notched-outline__trailing"></div>
		  </div>
		</div>
		<button type="button" class="mdc-icon-button material-icons ml-2">search</button>
	</div>
	@forelse($retailers as $retailer)
		<div class="text-center my-4">
			<a href="{{ route('retailer.index', ['retailer' => $retailer,]) }}">{{ $retailer->name }}</a>
		</div>
	@empty
		<div class="text-center my-4">
			<span>没有关注的卖家</span>
		</div>
	@endforelse
	
</div>
@endsection