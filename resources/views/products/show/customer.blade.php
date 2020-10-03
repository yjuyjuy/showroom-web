<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->getSizePrice('retail') as $size => $data)
<div><span>{{ $size }} - &yen;{{$data['price']}} - {{$data['retailer']}} - {{$data['updated_at']->isoFormat('MMMDo')}}</span></div>
	@empty
	<span>{{ __('not available from your following sellers') }}</span>
	@endforelse
</div>
