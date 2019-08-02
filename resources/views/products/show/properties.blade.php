<div class="d-flex flex-column products-show__info--properties">
	<div class="">
		<a id="product-brand" href="{{ route('products.index',['brand' => [$product->brand->id]]) }}"
			 class="mdc-typography--headline6">
			{{ __($product->brand->full_name) }}</a>
	</div>
	<div class="mdc-typography--headline6">
		<a id="product-season" href="{{ route('products.index',['season' => [$product->season->id]]) }}">{{ __($product->season->name) }}</a>
		<a id="product-name" href="{{ route('products.show',['product' => $product]) }}" onclick="event.preventDefault(); window.location.replace(this.href);">
			{{ $product->name_cn }}
		</a>
		<span id="product-color">
			{{ __($product->color->name) }}
		</span>
	</div>
	<div class="my-2"></div>
	<div>
		<span id="product-name">
			{{ $product->name }}
		</span>
	</div>
	@can('update',$product)
	@if($product->designerStyleId)
	<div class="">
		{{$product->designerStyleId}}
	</div>
	@endif
	<div class="">
		<span class="mr-2">{{ $product->id }}</span>
		<a href="{{ route('products.edit',['product' => $product ]) }}" onclick="event.preventDefault(); window.location.replace(this.href);">{{ __('edit') }}</a>
	</div>
	@endcan
</div>
