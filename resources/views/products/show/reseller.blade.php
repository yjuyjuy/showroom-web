<div class="d-flex flex-column products-show__info--vendor">
	@if($product->offers->isNotEmpty())

		@foreach($product->offers as $offer)
		<div class="price-grid my-3">
			<div class="font-weight-bold price-grid__header">
				<span class="price-grid__title">
					{{$offer->vendor->name.' - '.__($offer->vendor->city)}}
				</span>
			</div>
			@foreach($offer->prices as $size => $price)
			<div class="price-grid__row">
				<span class="price-grid__col">{{ $size }}</span>
				<span class="price-grid__col">&yen;{{ $price }}</span>
				<span class="price-grid__col text-center">-</span>
				<div class="price-grid__col d-flex justify-content-between align-items-center" style="margin: -12px;">
					<button type="button" class="mdc-icon-button material-icons" style="color: grey;">remove</button>
					<span>-</span>
					<button type="button" class="mdc-icon-button material-icons" style="color: grey;">add</button>
				</div>
			</div>
			@endforeach
		</div>
		@endforeach

	@else
	<span>暂无调货报价</span>
	@endif
</div>
