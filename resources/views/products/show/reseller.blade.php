@if($user->is_reseller)
<div class="d-flex flex-column products-show__info--vendor">
	@if($product->offers->isNotEmpty())
	<span class="">{{ __('Order price') }}</span>
		@foreach($product->getSizePrice('offer') as $size => $data)
		<span>{{$size}} - &yen;{{$data['price']}} - {{$data['vendor']}}</span>
		@endforeach
	@else
	<span>{{ __('no offer') }}</span>
	@endif
</div>
@endif
