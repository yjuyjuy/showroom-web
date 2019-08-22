@extends('layouts.app')

@section('title',$product->displayName().' - '.$retailer->name)

@section('content')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>

	<div class="content-container d-flex flex-column">
		@include('products.show.properties')
		<div class="d-flex flex-column products-show__info--customer">
			@forelse($product->getSizePrice('retail') as $size => $data)
			<div><a href="{{ $data['link'] ?? '#'}}" class="size-price">{{ $size }} - &yen;{{$data['price']}} - {{$data['retailer']}}</a></div>
			@empty
			<span class="not-available">{{ __('not available') }}</span>
			@endforelse
		</div>
		<?php $retail_with_link = $product->retails->where('link')->first(); ?>
		@if($retail_with_link)		
		<div class="w-100 d-block">
			<a href="{{ $retail_with_link->link }}" class="mdc-button mdc-button--unelevated">{{ __('Where to buy') }}</a>
		</div>
		@endif
		
	</div>
</div>
@endsection
