<div class="d-flex flex-column products-show__info--customer">
	@if($product->price)
		<span class="size-price">&yen;{{$product->price}}</span>
	@else
	<span>{{ __('not available') }}</span>
	@endif
</div>
<div>
	<a href="{{ $product->url }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank">
		<span class="mdc-button__label">{{ __('Link to page') }}</span>
	</a>
</div>
