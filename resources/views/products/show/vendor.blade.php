<div class="d-flex flex-column products-show__info--vendor">

	@if($product->prices->isNotEmpty())
	<div class="d-flex flex-column">
		<span class="">{{ __('Order price') }}</span>
		@foreach($product->getSizePrice('resell') as $size => $price)
			<span class="">{{$size}} - &yen;{{$price}}</span>
		@endforeach
	</div>
	@endif

	@if($product->prices->firstWhere('vendor_id',$vendor->id))
	<div class="price-grid">
		<div class="price-grid__header">
			<span class="price-grid__title">{{ __('My offer') }}</span>
		</div>
		<div class="price-grid__row">
			<span class="price-grid__col">{{ __('size') }}</span>
			<span class="price-grid__col">{{ __('cost') }}</span>
			<span class="price-grid__col">{{ __('resell') }}</span>
			<span class="price-grid__col">{{ __('retail') }}</span>
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
				<a href="{{route('prices.edit',['price'=>$price])}}" class="mdc-button" onclick="event.preventDefault(); window.location.replace(this.href);">{{ __('edit') }}</a>
				<button type="button" class="mdc-button mdc-button--error" @click.prevent="deletePrice({{$price->id}})">{{ __('delete') }}</button>
			</div>
		@endforeach
	</div>
	@else
	<div class="d-flex justify-content-end">
		<a href="{{route('prices.create',['product' => $product])}}" class="mdc-button mdc-button--unelevated" onclick="event.preventDefault(); window.location.replace(this.href);">
			<span class="mdc-button__label">{{ __('add price') }}</span>
		</a>
	</div>
	@endif
</div>
