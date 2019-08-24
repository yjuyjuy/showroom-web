<div class="d-flex flex-column products-show__info--vendor">
	@if($product->offers->isNotEmpty())
		<span class="">{{ __('Order price') }}</span>
			@foreach($product->getSizePrice('offer') as $size => $data)
				<div><a href="{{ $data['link'] }}" class="">{{$size}} - &yen;{{$data['price']}} - {{$data['vendor']}}</a></div>
			@endforeach
		@else
			<span class="not-available">{{ __('no offer') }}</span>
		@endif
</div>
