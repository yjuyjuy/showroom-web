<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->size_price as $size => $price)
	<span class="size-price">{{ $size }} - {{$price}}</span>
	@empty
	<span class="not-available">{{ __('not available') }}</span>
	@endforelse
	<a href="{{ $product->url }}" target="_blank">{{ __('link to farfetch') }}</a>
</div>
