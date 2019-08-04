<div class="d-flex flex-column products-show__info--vendor">
	<?php $product->load(['offers' => function($query) use ($user, $product) { $query->whereIn('vendor_id',$user->vendors->pluck('id'))->where('product_id',$product->id); });?>
	@if($product->offers->isNotEmpty())
	<div class="d-flex flex-column">
		<span class="">{{ __('Order price') }}</span>
		@foreach($product->getSizePrice('offer') as $size => $price)
			<span class="">{{$size}} - &yen;{{$price}}</span>
		@endforeach
	</div>
	@endif
</div>
