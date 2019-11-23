<div class="d-flex flex-column products-show__info--admin">
	<?php $product->prices->loadMissing('vendor'); ?>
	@foreach($product->prices as $price)
	<div class="price-grid mb-3">
		<div class="font-weight-bold price-grid__header">
			<a href="{{route('prices.edit',['price'=>$price])}}" class="price-grid__title">
				{{$price->vendor->name.' - '.__($price->vendor->city)}}
			</a>
		</div>
		@foreach($price->data as $row)
		<div class="price-grid__row">
			<span class="price-grid__col">{{ $row['size'] }}</span>
			<span class="price-grid__col">&yen;{{ $row['offer'] }}</span>
			<span class="price-grid__col">&yen;{{ $row['retail'] }}</span>
			<div class="price-grid__col d-flex justify-content-between align-items-center" style="margin: -12px;">
				<button type="button" class="mdc-icon-button material-icons" onclick="axios.post('{{ route('prices.subtract', ['price' => $price, 'size' => $row['size']]) }}').then(response=>window.location.reload()).catch(error=>window.alert('action failed'))">remove</button>
				<span>{{ $row['stock'] ?? 999 }}</span>
				<button type="button" class="mdc-icon-button material-icons" onclick="axios.post('{{ route('prices.add', ['price' => $price, 'size' => $row['size']]) }}').then(response=>window.location.reload()).catch(error=>window.alert('action failed'))">add</button>
			</div>
		</div>
		@endforeach
		<div class="price-grid__footer text-right">
			<a href="{{route('prices.edit',['price'=>$price])}}" class="mdc-button">{{ __('edit') }}</a>
			<button type="button" class="mdc-button mdc-button--error" onclick="delete_price({{$price->id}})">
				<span class="mdc-button__label">{{ __('delete') }}</span>
			</button>
		</div>
	</div>
	@endforeach
	<div class="d-flex justify-content-end">
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button">
				<span class="mdc-button__label">{{ __('add price') }}</span>
				<i class="material-icons mdc-button__icon" aria-hidden="true">arrow_drop_down</i>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@foreach(\App\Vendor::whereNotIn('id',$product->prices->pluck('vendor_id')->toArray())->get() as $vendor)
					<a href="{{route('prices.create',['product' => $product, 'vendor' => $vendor])}}" class="mdc-list-item mdc-list-item__text text-left" role="menuitem">{{$vendor->name}}</a>
					@endforeach
			  </ul>
			</div>
		</div>
	</div>
</div>
