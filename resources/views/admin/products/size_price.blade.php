
<div class="py-4 my-2 mx-auto col-10 col-md-8 border">

	<div class="">最低调货价</div>

	@forelse($product->fresh()->getSizePrice('resell') as $size => $price)
	<div class="">{{ $size }} - &yen;{{ $price }}</div>
	@empty
	<div class="">暂无报价</div>
	@endforelse

</div>

<div class="py-4 my-2 mx-auto col-10 col-md-8 border">

	<div class="">最低零售价</div>

	@forelse($product->fresh()->getSizePrice('retail') as $size => $price)
	<div class="">{{ $size }} - &yen;{{ $price }}</div>
	@empty
	<div class="">暂无报价</div>
	@endforelse

</div>
