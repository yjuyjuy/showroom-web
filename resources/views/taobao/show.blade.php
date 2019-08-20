@extends('layouts.app')

@section('title', $product->title.' - '.$product->shop->name)

@section('content')
<div class="images-content-container">
	<div class="images-container">
		<img src="{{ $product->image }}" alt="">
	</div>
	<div class="content-container d-flex flex-column">
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
				<span class="not-available">{{ __('not available') }}</span>
			</div>
			@endforelse
		<div class="">
			<a type="button" name="button" class="mdc-button mdc-button--unelevated" href="{{ $product->url }}" target="_blank">
				<span class="mdc-button__label">{{ __('Taobao link') }}</span>
			</a>
	</div>
	</div>
</div>
@endsection
