@if($user->is_reseller)
<div class="d-flex flex-column products-show__info--vendor">
	@if($product->offers->isNotEmpty())
		<div class="">
			<span class="">{{ __('Order price') }}</span>
			<button type="button" class="mdc-button" onclick='copy_to_clipboard(@json($product->offers_to_string))'>
				<span class="mdc-button__label">复制尺码价格</span>
			</button>
		</div>
		@foreach($product->getSizePrice('offer') as $size => $data)
		<span>{{$size}} - &yen;{{$data['price']}} - {{$data['vendor']}}</span>
		@endforeach
	@else
	<span>{{ __('no offer') }}</span>
	@endif
</div>
@endif
