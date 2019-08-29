@if($user->is_reseller)
<div class="d-flex flex-column products-show__info--vendor">
	@if($product->offers->isNotEmpty())
		<div class="">
			<span class="">{{ __('Order price') }}</span>
		</div>
		@foreach($product->getSizePrice('offer') as $size => $data)
		<span>{{$size}} - &yen;{{$data['price']}} - {{$data['vendor']}}</span>
		@endforeach
		<div class="mt-2">
			<input type="text" value="{{ $product->offers_to_string }}" style="opacity:0;position:fixed;">
			<button type="button" class="mdc-button" onclick="var input = this.parentElement.firstChild;input.setSelectionRange(0,input.value.length);input.select();document.execCommand('copy');input.blur();">
				<span class="mdc-button__label">复制尺码价格</span>
			</button>
		</div>
	@else
	<span>{{ __('no offer') }}</span>
	@endif
</div>
@endif
