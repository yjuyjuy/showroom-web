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
</div>
