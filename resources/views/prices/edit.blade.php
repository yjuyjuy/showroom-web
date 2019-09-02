@extends('layouts.app')

@section('title', '修改报价 - '.$product->displayName())

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
			<a href="#" class="mdc-button mdc-button--outlined" onclick="event.preventDefault(); window.history.back();">
				<span class="mdc-button__label">{{ __('Back') }}</span>
			</a>
			<button type="button" class="mdc-button mdc-button--outlined ml-2" form="update-form"
							onclick="var data = new FormData(this.form);
							axios.post('{{route('prices.update',['price' => $price ])}}',data)
							.then(response=>{ window.location.replace(response.data.redirect) })">
				<span class="mdc-button__label">{{ __('submit') }}</span>
			</button>
			<button type="button" class="mdc-button mdc-button--outlined mdc-button--error ml-2" onclick="delete_price({{$price->id}})">
				<span class="mdc-button__label">{{ __('delete all') }}</span>
			</button>
		</div>
	</div>
</div>
@endsection
