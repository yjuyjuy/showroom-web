<div class="d-flex flex-column products-show__info--customer">
	@if($product->sizes)
		@foreach(explode(',',$product->sizes) as $size)
		<span class="size-price">{{ $size }} - &yen;{{ $product->price }}</span>
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
		<a href="{{ route('end.export', ['end_product' => $product,]) }}" class="ml-2 mdc-button mdc-button--unelevated">
			@if(\App\Product::where('designer_style_id', $product->sku)->where('brand_id', $product->mapped_brand_id)->count() <= 0)
			<span class="mdc-button__label">上架</span>
			@else
			<span class="mdc-button__label">导入</span>
			@endif
		</a>
	@endif
</div>
