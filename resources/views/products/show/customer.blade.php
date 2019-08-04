<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->getSizePrice('retail') as $size => $price)
	<span class="size-price">{{ $size }} - &yen;{{$price}}</span>
	@empty
	<span class="not-available">{{ __('not available') }}</span>
	@endforelse
</div>
