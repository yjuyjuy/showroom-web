<div class="d-flex flex-column products-show__info--vendor">
	@if($price = $product->prices->firstWhere('vendor_id',$vendor->id))
	<div class="price-grid">
		<div class="price-grid__header">
			<span class="price-grid__title">{{ __('My offer') }}</span>
		</div>
		<div class="price-grid__row">
			<span class="price-grid__col">{{ __('size') }}</span>
			<span class="price-grid__col">{{ __('cost') }}</span>
			<span class="price-grid__col">{{ __('offer') }}</span>
			<span class="price-grid__col">{{ __('retail') }}</span>
		</div>
		@foreach($price->data as $row)
			<div class="price-grid__row">
				<span class="price-grid__col">{{ $row['size'] }}</span>
				<span class="price-grid__col">&yen;{{$row['cost']}}</span>
				<span class="price-grid__col">&yen;{{$row['offer']}}</span>
				<span class="price-grid__col">&yen;{{$row['retail']}}</span>
			</div>
		@endforeach
		<div class="price-grid__footer text-right">
			<a href="{{route('prices.edit',['price'=>$price])}}" class="mdc-button">{{ __('edit') }}</a>
			<button type="button" class="mdc-button mdc-button--error" onclick="delete_price({{$price->id}})">
				<span class="mdc-button__label">{{ __('delete') }}</span>
			</button>
		</div>
	</div>
	@else
	<div class="d-flex justify-content-end">
		<a href="{{route('prices.create',['product' => $product])}}" class="mdc-button mdc-button--unelevated">
			<span class="mdc-button__label">{{ __('add price') }}</span>
		</a>
	</div>
	@endif
</div>
