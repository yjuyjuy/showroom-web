@extends('layouts.app')

@section('title',__('Add price').'-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>
	<div class="content-container">
		@if(auth()->user()->isSuperAdmin())
		<input type="hidden" name="vendor" value="{{$vendor->id}}" form="create-form">
		<div class="w-100">
			<div class="font-weight-bold text-left">
				{{$vendor->name.' - '.$vendor->city}}
			</div>
		</div>
		@endif

		<form action="{{route('prices.store',['product'=>$product])}}" method="post" id="create-form">
			@csrf
			<prices-editor v-bind:input="[]"></prices-editor>
		</form>

		<div class="d-flex justify-content-end">
			<a href="{{ route('products.show', ['product' => $product,]) }}" class="mdc-button mdc-button--outlined" onclick="event.preventDefault(); window.location.replace(this.href);">
				<span class="mdc-button__label">{{ __('Back') }}</span>
			</a>
			<button type="button" class="mdc-button mdc-button--outlined ml-2" form="create-form"
							onclick="var data = new FormData(this.form);
							axios.post('{{route('prices.store',['product' => $product ])}}',data)
							.then(response=>{ window.location.replace(response.data.redirect) })">
				<span class="mdc-button__label">{{ __('submit') }}</span>
			</button>
		</div>
	</div>
</div>
@endsection
