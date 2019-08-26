<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->size_price as $size => $price)
	<span class="size-price">{{ $size }} - {{$price}}</span>
	@empty
	<span>{{ __('not available') }}</span>
	@endforelse
</div>
<div>
	<a href="{{ $product->url }}" target="_blank" class="mdc-button">{{ __('link') }}</a>
</div>
