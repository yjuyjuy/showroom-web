@extends('layouts.app')

@section('title', $shop->name.' - 管理中心')

@section('content')
<div id="taobao-admin" class="m-2 d-flex w-100 flex-wrap">
	<div id="unlink-product-container">
		<div class="mx-4">
			<div class="w-100">
				<span>未关联商品</span>
			</div>
			<div class="d-flex flex-column">
				@foreach($shop->prices as $price)
					<div class="d-flex justify-content-center my-4 link-product-component">
						<div class="d-flex flex-column justify-content-around mr-2" style="width:80%;">
							<div class=""><a href="{{$price->taobao_product->url}}" target="_blank">{{ $price->description }}</a></div>
							<div class=""><input type="text" value="" list="product-datalist" class="simple-text-input product-selector" data-id="{{$price->id}}" placeholder="搜索商品"></div>
							<div class="text-right"><button type="button" class="mdc-button ignore-button">忽略</button><button type="button" class="mdc-button confirm-button">确定</button></div>
						</div>
						<div class="" style="width:20%;">
							<a href="#" target="_blank">
								<img class="product-thumbnail" src="{{ asset('storage/icons/ImagePlaceholder.svg') }}" alt="">
							</a>
						</div>
					</div>
				@endforeach
				<datalist id="product-datalist">
					<option value="" data-image-src="{{ asset('storage/icons/ImagePlaceholder.svg') }}" data-product-href="#" hidden disabled></option>
					@foreach(\App\Product::all() as $product)
						<option value="{{ $product->id }}" data-image-src="{{ $product->image->url ?? asset('storage/icons/ImagePlaceholder.svg') }}" data-product-href="{{ route('products.show',['product' => $product]) }}">{{ $product->displayName() }} {{ __($product->color->name) }}</option>
					@endforeach
				</datalist>
			</div>
		</div>
	</div>
</div>
@endsection
