<div class="d-flex flex-column products-show__info--vendor">
	@if($product->offers->isNotEmpty())
		<div class="">
			<span class="">{{ __('Order price') }}</span>
		</div>
		@foreach($product->getSizePrice('offer') as $size => $data)
		<span>{{$size}} - &yen;{{$data['price']}} - {{$data['vendor']}}</span>
		@endforeach
		<div class="mt-2">
			<input type="text" value="{{ $product->offers_to_string }}" style="opacity:0;position:absolute;left:-100%;">
			<button type="button" class="mdc-button" onclick="var input = this.parentElement.firstChild;input.focus();input.setSelectionRange(0,input.value.length);document.execCommand('copy');input.blur();">
				<span class="mdc-button__label">复制尺码价格</span>
			</button>
		</div>
	@else
	<span>{{ __('no offer') }}</span>
	@endif
</div>
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
