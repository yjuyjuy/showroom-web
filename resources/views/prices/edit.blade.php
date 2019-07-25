@extends('layouts.app')

@section('title',__('Edit price').'-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>
	<div class="content-container">
		@if(auth()->user()->isSuperAdmin())
		<div class="w-100">
			<div class="font-weight-bold text-left">
				{{$price->vendor->name.' - '.$price->vendor->city}}
			</div>
		</div>
		@endif

		<form action="{{route('prices.update',['price' => $price])}}" method="post" id="update-form">
			@csrf
			@method('PATCH')
			<prices-editor v-bind:input='@json(array_values($price->data))'></prices-editor>
		</form>

		<div class="d-flex justify-content-end">
			<button type="button" class="mdc-button mdc-button--outlined" onclick="window.history.back()">
				<span class="mdc-button__label">{{ __('Back') }}</span>
			</button>
			<button type="submit" class="mdc-button mdc-button--outlined ml-2" form="update-form">
				<span class="mdc-button__label">{{ __('submit') }}</span>
			</button>
			<button type="button" class="mdc-button mdc-button--outlined mdc-button--error ml-2" @click="deletePrice({{$price->id}})">
				<span class="mdc-button__label">{{ __('delete all') }}</span>
			</button>
		</div>
	</div>
</div>
@endsection
