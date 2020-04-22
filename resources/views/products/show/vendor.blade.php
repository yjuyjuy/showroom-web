<?php $vendor = auth()->user()->vendor; ?>
<div class="d-flex flex-column products-show__info--vendor">
	@foreach($product->prices as $price)
	<div class="price-grid my-3">
		<div class="price-grid__header">
			<span class="price-grid__title">{{ __('My offer') }}</span>
		</div>
		<div class="price-grid__row">
			<span class="price-grid__col">{{ __('size') }}</span>
			<span class="price-grid__col">{{ __('offer') }}</span>
			<span class="price-grid__col">{{ __('retail') }}</span>
			<span class="price-grid__col">{{ __('stock') }}</span>
		</div>
		@foreach($price->data as $row)
			<div class="price-grid__row">
				<span class="price-grid__col">{{ $row['size'] }}</span>
				<span class="price-grid__col">&yen;{{$row['offer']}}</span>
				<span class="price-grid__col">&yen;{{$row['retail']}}</span>
				<div class="price-grid__col d-flex justify-content-between align-items-center" style="margin: -12px;">
					<button type="button" class="mdc-icon-button material-icons" onclick="axios.post('{{ route('prices.subtract', ['price' => $price, 'size' => $row['size']]) }}').then(response=>window.location.reload()).catch(error=>window.alert('action failed'))">remove</button>
					<span>{{ $row['stock'] ?? 999 }}</span>
					<button type="button" class="mdc-icon-button material-icons" onclick="axios.post('{{ route('prices.add', ['price' => $price, 'size' => $row['size']]) }}').then(response=>window.location.reload()).catch(error=>window.alert('action failed'))">add</button>
				</div>
			</div>
		@endforeach
		<div class="price-grid__footer text-right">
			<span>最后更新: {{ $price->updated_at }}</span>
			<a href="{{route('prices.edit',['price'=>$price])}}" class="mdc-button">{{ __('edit') }}</a>
			<button type="button" class="mdc-button mdc-button--error" onclick="delete_price({{$price->id}})">
				<span class="mdc-button__label">{{ __('delete') }}</span>
			</button>
		</div>
	</div>
	@endforeach

	@if($product->offers->isNotEmpty())

		@foreach($product->offers->where('vendor_id', '!=', $vendor->id) as $offer)
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
	<div class="my-3">暂无调货报价</div>
	@endforelse

	@if($product->prices->where('vendor_id',$vendor->id)->isEmpty())
	<div class="d-flex justify-content-end my-3">
		<a href="{{route('prices.create',['product' => $product])}}" class="mdc-button mdc-button--unelevated">
			<span class="mdc-button__label">添加报价</span>
		</a>
	</div>
	@endif
</div>
