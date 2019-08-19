<div class="d-flex flex-column products-show__info--properties">
	<div class="">
		<a id="product-brand" href="{{ route('farfetch.index',['designer' => [$product->designer]]) }}"
			 class="mdc-typography--headline6">
			{{ __($product->designer->name) }}</a>
	</div>
	<div class="mdc-typography--headline6" style="text-transform: capitalize;">
		<span id="product-name">
			{{ $product->shortDescription }}
		</span>
		<span id="product-color">
			{{ __($product->colors) }}
		</span>
	</div>
	<div class="mt-1">
		<a href="#" onclick="event.preventDefault(); navigator.clipboard.writeText(this.textContent);">{{$product->designerStyleId}}</a>
	</div>
</div>
