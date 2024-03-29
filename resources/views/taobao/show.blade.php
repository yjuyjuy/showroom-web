@extends('layouts.app')

@section('title', $product->title.' - 淘宝店铺'.$product->shop->name)

@section('content')
<div class="container">
	<div class="">
		<img class="lazy" data-src="{{ $product->image }}" alt="">
	</div>
	<div class="container__content d-flex flex-column">
		<div class="d-flex flex-column products-show__info--properties">
			<div class="mdc-typography--headline6">
				<a class="product-name" href="{{ $product->url }}" target="_blank">{{ $product->title }}</a>
			</div>
		</div>
			@forelse($product->prices as $price)
			<div class="d-flex flex-column products-show__info--customer">
				<span>{{ $product->properties[$price->property_id]['name'] }}</span>
				@foreach($price->prices as $size => $price)
					<span class="size-price">{{ $size }} - &yen;{{ $price }}</span>
				@endforeach
			</div>
			@empty
			<div class="">
				<span>{{ __('not available') }}</span>
			</div>
			@endforelse
		<div class="">
			<a type="button" class="mdc-button mdc-button--unelevated" href="{{ $product->url }}" target="_blank">
				<span class="mdc-button__label">{{ __('Taobao link') }}</span>
			</a>
	</div>
	</div>
</div>
@include('layouts.back_fab')
@endsection
