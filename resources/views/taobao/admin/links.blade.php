@extends('layouts.app')

@section('title', '关联商品 - 淘宝管理')

@section('content')
<div class="m-2 d-flex justify-content-center">
	<div class="d-flex flex-column">
		@if($shop)
		<div class="d-flex justify-content-center">
			<a href="{{ route('taobao.admin.links', ['shop' => $shop,]) }}" class="mdc-button w-100"><span class="mdc-button__label">未关联</span></a>
			<a href="{{ route('taobao.admin.linked', ['shop' => $shop,]) }}" class="mdc-button w-100"><span class="mdc-button__label">已关联</span></a>
			<a href="{{ route('taobao.admin.ignored', ['shop' => $shop,]) }}" class="mdc-button w-100"><span class="mdc-button__label">已忽略</span></a>
		</div>
		@endif
		@forelse($prices as $price)
			<div class="product-card">
				<div class="product-card__left">
					<a href="#" target="_blank">
						<img class="product-thumbnail" src="{{ $price->product->image->url ?? $price->taobao_product->image ?? asset('storage/icons/ImagePlaceholder.svg') }}" alt="">
					</a>
				</div>
				<div class="product-card__right">
					<div><a href="{{$price->taobao_product->url}}" target="_blank" class="product-card__headline">{{ $price->description }}</a></div>
					<div><input type="text" size="15" value="{{$price->product_id ?? ''}}" list="product-datalist" class="simple-text-input" data-id="{{$price->id}}" placeholder="{{ $price->taobao_product->properties[$price->property_id]['name'] }}"></div>
					<div class="">
						<button type="button" class="mdc-button mdc-button--outlined mdc-button--error ignore-button mr-2">忽略</button>
						<button type="button" class="mdc-button mdc-button--unelevated confirm-button">确定</button>
					</div>
				</div>
			</div>
		@empty
			<div class="d-flex justify-content-center align-items-center">
				<span class="mdc-typography--headline5">没有搜索到商品</span>
			</div>
		@endforelse
	</div>
	<datalist id="product-datalist">
		<option value="" data-image-src="{{ asset('storage/icons/ImagePlaceholder.svg') }}" data-product-href="#" hidden disabled></option>
		@foreach(\App\Product::orderBy('season_id')->orderBy('category_id')->get() as $product)
		<option value="{{ $product->id }}" data-image-src="{{ $product->image->small ?? asset('storage/icons/ImagePlaceholder.svg') }}" data-href="{{ route('products.show',['product' => $product,]) }}">{{ $product->displayName() }} {{ __($product->color->name) }}</option>
		@endforeach
	</datalist>
</div>
@endsection
