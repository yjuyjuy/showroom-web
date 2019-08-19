@extends('layouts.app')

@section('title', $shop->name)

@section('content')
<div id="products-index" class="">
	<ul class="mdc-image-list main-image-list">
		@foreach($products as $product)
		<li class="mdc-image-list__item">
			<a href="{{ route('products.show',['product' => $product,]) }}">
				<div class="">
					<img class="mdc-image-list__image" src="{{ $product->image->url ?? asset('storage/icons/ImagePlaceholder.svg') }}">
				</div>
				<div class="mdc-image-list__supporting">
					<span class="mdc-image-list__label product-name">{{ $product->displayName() }}</span>
				</div>
			</a>
		</li>
		@endforeach
	</ul>
</div>
@endsection
