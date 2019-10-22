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
			<span class="price-grid__col">&yen;{{ $row['cost'] }}</span>
			<span class="price-grid__col">&yen;{{ $row['offer'] }}</span>
			<span class="price-grid__col">&yen;{{ $row['retail'] }}</span>
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
	<?php $farfetch_products = \App\FarfetchProduct::where('product_id', $product->id)->get(); ?>
	<?php $end_products = \App\EndProduct::where('product_id', $product->id)->get(); ?>
	@if($farfetch_products->isNotEmpty() || $end_products->isNotEmpty())
	<div class="d-flex justify-content-end">
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button">
				<span class="mdc-button__label">{{ __('link') }}</span>
				<i class="material-icons mdc-button__icon" aria-hidden="true">arrow_drop_down</i>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
				<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@foreach($farfetch_products as $index => $farfetch_product)
					<a href="{{route('farfetch.show',['product' => $farfetch_product,])}}" class="mdc-list-item mdc-list-item__text text-left" role="menuitem">Farfetch链接{{$index + 1}}</a>
					@endforeach
					@foreach($end_products as $index => $end_product)
						<a href="{{route('end.show',['product' => $end_product,])}}" class="mdc-list-item mdc-list-item__text text-left" role="menuitem">End链接{{$index + 1}}</a>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
	@endif
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
