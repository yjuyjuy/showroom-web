<div class="d-flex flex-column products-show__info--customer">
	@if($product->size_price)
		@foreach($product->size_price as $size => $price)
		<span class="size-price">{{ $size }} - &yen;{{$price}}</span>
		@endforeach
	@else
	<span>{{ __('not available') }}</span>
	@endif
</div>
<div>
	<a href="{{ $product->url }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank">
		<span class="mdc-button__label">{{ __('Link to page') }}</span>
	</a>
	@if(auth()->user()->isSuperAdmin())
		<a href="{{ route('farfetch.export', ['farfetch_product' => $product,]) }}" class="ml-2 mdc-button mdc-button--unelevated">
			@if(\App\Product::where('designer_style_id', $product->designer_style_id)->where('brand_id', $product->designer->brand_id)->count() <= 0)
			<span class="mdc-button__label">上架</span>
			@else
			<span class="mdc-button__label">导入</span>
			@endif
		</a>
	@endif
</div>
