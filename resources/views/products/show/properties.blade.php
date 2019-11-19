<div class="d-flex flex-column products-show__info--properties">

	<div class="mdc-typography--headline6">
		<a id="product-brand" href="{{ route('products.index',['brand' => [$product->brand_id,]]) }}">
			{{ __($product->brand->full_name ?? '') }}</a>
		<a id="product-brand" href="{{ route('products.index',['category' => [$product->category_id,]]) }}">
			{{ __($product->category->name ?? '') }}</a>
	</div>
	<div class="mdc-typography--headline6">
		<a class="product-name" href="{{ route('products.show',['product' => $product]) }}">
			{{ $product->name_cn }}
		</a>
	</div>

	<div class="mt-2">
		<div style="text-transform:capitalize;">{{ $product->name }} {{ __($product->color->name ?? '') }}</div>
		<div class="">
			@if($product->season_id)<span class="">{{$product->season->name}} {{ __('season') }}</span>@endif
			@if($product->designer_style_id)<span class="">{{ __('designer_style_id') }}: {{$product->designer_style_id}}</span>@endif
		</div>
		@can('update',$product)
		<div class="">
			<span class="">ID: {{ $product->id }}</span>
			<a href="{{ route('products.edit',['product' => $product ]) }}">{{ __('edit') }}</a>
		</div>
		@endcan
	</div>

</div>
