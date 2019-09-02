<div class="d-flex flex-column products-show__info--properties">

	<div class="mdc-typography--headline6">
		<a id="product-brand" href="{{ route('products.index',['brand' => [$product->brand_id,]]) }}">
			{{ __($product->brand->full_name) }}</a>
	</div>
	<div class="mdc-typography--headline6">
		<a class="product-name" href="{{ route('products.show',['product' => $product]) }}">
			{{ $product->name_cn }}
		</a>
	</div>

	@can('update',$product)
	<div class="mt-2">
		<div style="text-transform:capitalize;">{{ $product->name }} {{ __($product->color->name) }}</div>
		@if($product->designerStyleId)<div class="">{{ __('designerStyleId') }}: {{$product->designerStyleId}}</div>@endif
		<div class="">
			<span class="">ID: {{ $product->id }}</span>
			<a href="{{ route('products.edit',['product' => $product ]) }}">{{ __('edit') }}</a>
		</div>
	</div>
	@endcan

</div>
