<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->size_price as $size => $price)
	<span class="size-price">{{ $size }} - &yen;{{$price}}</span>
	@empty
	<span class="not-available">not available</span>
	@endforelse
</div>
