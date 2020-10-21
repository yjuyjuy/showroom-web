@extends('layouts.app')

@section('title', '淘宝店铺 - '.$shop->name)

@section('content')
<div id="products-index" class="">
	<ul class="mdc-image-list main-image-list">
		@foreach($products as $product)
		<li class="mdc-image-list__item">
			<a href="{{ route('taobao.products.show',['shop' => $shop, 'product' => $product,]) }}">
				<div class="mdc-image-list__image-aspect-container">
					<img class="mdc-image-list__image lazy" data-src="{{ $product->image ?? secure_asset('storage/icons/ImagePlaceholder.svg') }}">
				</div>
				<div class="mdc-image-list__supporting">
					<span class="mdc-image-list__label product-name text-wrap">{{ $product->title }}</span>
					<span class="mdc-image-list__label">&yen;{{ $product->price ?? ' -' }}</span>
				</div>
			</a>
		</li>
		@endforeach
	</ul>
</div>
@endsection
