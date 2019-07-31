<div class="d-flex flex-column products-show__info--properties">
	<div class="">
		<a id="product-brand" href="{{ route('products.index',['brand' => [$product->brand->id]]) }}">
			{{ __($product->brand->full_name) }}</a>
	</div>
	<div>
		<a id="product-season" href="{{ route('products.index',['season' => [$product->season->id]]) }}">{{ __($product->season->name) }}</a>
		<span id="product-name">
			{{ $product->name_cn }}
		</span>
		<span id="product-color">
			{{ __($product->color->name) }}
		</span>
	</div>
	<div>
		<span id="product-name">
			{{ $product->name }}
		</span>
	</div>
	@can('update',$product)
	<div class="">
		<span class="mr-2">{{ $product->id }}</span>
		<a href="{{ route('products.edit',['product' => $product ]) }}" onclick="event.preventDefault(); window.location.replace(this.href);">{{ __('edit') }}</a>
	</div>
	@endcan
</div>
