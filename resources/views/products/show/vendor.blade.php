<div class="d-flex flex-column products-show__info--vendor">

	@if($product->prices->isNotEmpty())
	<div class="d-flex flex-column">
		<span class="">调货价</span>
		@foreach($product->getSizePrice('resell') as $size => $price)
			<span class="">{{ $size }} - &yen;{{$price}}</span>
		@endforeach
	</div>
	@endif

	@if($product->prices->firstWhere('vendor_id',$vendor->id))
	<div class="price-grid">
		<div class="price-grid__header">
			<span class="price-grid__title">我的报价</span>
		</div>
		<div class="price-grid__row">
			<span class="price-grid__col">尺码</span>
			<span class="price-grid__col">成本</span>
			<span class="price-grid__col">调货</span>
			<span class="price-grid__col">零售</span>
		</div>
		@foreach($product->prices->where('vendor_id',$vendor->id) as $price)
			@foreach($price->data as $row)
				<div class="price-grid__row">
					<span class="price-grid__col">{{ $row['size'] }}</span>
					<span class="price-grid__col">&yen;{{$row['cost']}}</span>
					<span class="price-grid__col">&yen;{{$row['resell']}}</span>
					<span class="price-grid__col">&yen;{{$row['retail']}}</span>
				</div>
			@endforeach
			<div class="price-grid__footer text-right">
				<a href="{{route('prices.edit',['price'=>$price])}}" class="mdc-button">{{ __('edit') }}</a>
				<button type="button" class="mdc-button mdc-button--error" @click.prevent="deletePrice({{$price->id}})">{{ __('delete') }}</button>
			</div>
		@endforeach
	</div>
	@else
	<div class="d-flex justify-content-end">
		<a href="{{route('prices.create',['product' => $product])}}" class="mdc-button mdc-button--unelevated">
			<span class="mdc-button__label">{{ __('Add price') }}</span>
		</a>
	</div>
	@endif
</div>
