@if($product->offers->isNotEmpty())
<div class="d-flex flex-column products-show__info--vendor">
	<div class="d-flex flex-column">
		<span class="">{{ __('Order price') }}</span>
		@foreach($product->getSizePrice('offer') as $size => $data)
			<span class="">{{$size}} - &yen;{{$data['price']}} - {{$data['vendor']}}</span>
		@endforeach
	</div>
</div>
@endif
