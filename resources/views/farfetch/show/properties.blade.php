<div class="d-flex flex-column products-show__info--properties">
	<div class="">
		<a id="product-brand" href="{{ route('farfetch.index',['designer' => [$product->designer->id]]) }}">
			{{ __($product->designer->name) }}</a>
	</div>
	<div>
		<span id="product-name">
			{{ $product->shortDescription }}
		</span>
	</div>
	<div>
		<span id="product-color">
			{{ __($product->colors) }}
		</span>
	</div>
</div>
