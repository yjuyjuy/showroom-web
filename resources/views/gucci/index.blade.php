@extends('layouts.app')

@section('title')
@if ($category) {{ implode(' - ', array_map('__', explode('-', $category))).' - Gucci' }}
@else {{ 'Gucci' }} @endif
@endsection

@section('content')
<div id="products-index" class="">
	@php $retailer = \App\Retailer::find(2294995609); @endphp
	@include('retailer.banner')
	<div class="d-flex">
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>{{ __('category') }}</span></button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@foreach($categories as $value)
						<a class="mdc-list-item mdc-list-item__text" role="menuitem" href="{{ route('gucci.categories.index', ['category' => $value,]) }}">{{ implode(' - ', array_map('__', explode('-', $value))) }}</a>
					@endforeach
			  </ul>
			</div>
		</div>
	</div>
	@if($products->isEmpty())
		<div class="my-5 text-center">
			没有搜索到相关商品
		</div>
	@else
	<ul class="mdc-image-list main-image-list">
		@foreach($products->load('image') as $product)
		<li class="mdc-image-list__item">
			<a href="{{ route('gucci.show',['product' => $product,]) }}">
				<div class="">
					<img class="mdc-image-list__image lazy" data-src="{{ $product->image->url ?? '' }}">
				</div>
				<div class="mdc-image-list__supporting">
					<span class="mdc-image-list__label brand">Gucci</span>
					<span class="mdc-image-list__label product-name">{{ $product->name }}</span>
				</div>
			</a>
		</li>
		@endforeach
	</ul>
	@include('layouts.pages')
	@endif
@endsection
