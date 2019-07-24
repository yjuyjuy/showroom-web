<div class="d-flex flex-column products-show__info--admin">
	@if($product->prices->isNotEmpty())
	<div class="d-flex flex-column">
		<span class="">{{ __('Order price') }}</span>
		<?php $all_prices = $product->getAllPrices(); ?>
		@foreach($product->getSizePrice('resell') as $size => $price)
		<?php $vendor = \App\Vendor::find($all_prices->where('size',$size)->where('resell',$price)->first()['vendor']) ?>
 			<span class="">{{$size}} - &yen;{{$price}} - {{$vendor->name}} - {{$vendor->city}}</span>
 		@endforeach
	</div>
	@endif

	<?php $product->prices->loadMissing('vendor'); ?>
	@foreach($product->prices as $price)
	<div class="price-grid">
		<div class="font-weight-bold price-grid__header">
			<a href="{{route('prices.edit',['price'=>$price])}}" class="price-grid__title">
				{{$price->vendor->name.' - '.__($price->vendor->city)}}
			</a>
		</div>
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
				<a href="#" class="mdc-button mdc-button--error" @click.prevent="deletePrice({{$price->id}})">{{ __('delete') }}</a>
		</div>
	</div>
	@endforeach

	<div class="d-flex justify-content-end">
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button mdc-button--unelevated open-menu-button">
				<span class="mdc-button__label">{{ __('add price') }}</span>
				<i class="material-icons mdc-button__icon" aria-hidden="true">arrow_drop_down</i>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
			    @foreach(\App\Vendor::whereNotIn('id',$product->prices->pluck('vendor_id')->toArray())->get() as $vendor)
					<li class="mdc-list-item" role="menuitem">
			      <a href="{{route('prices.create',['product' => $product, 'vendor' => $vendor->id])}}" class="mdc-list-item__text w-100 text-left">{{$vendor->name}}</a>
			    </li>
					@endforeach
			  </ul>
			</div>
		</div>
	</div>
</div>
