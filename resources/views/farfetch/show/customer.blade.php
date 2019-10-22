<div class="d-flex flex-column products-show__info--customer">
	@if($product->size_price)
	@foreach($product->size_price as $size => $price)
	<span class="size-price">{{ $size }} - &yen;{{$price}}</span>
	@endforeach
	@else
	<span>{{ __('not available') }}</span>
	@endif
</div>
<div>
	<a href="{{ $product->url }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank">
		<span class="mdc-button__label">打开Farfetch</span>
	</a>
	@if($product->product)
	<a href="{{ route('products.show', ['product' => $product->product,]) }}" class="mdc-list-item" role="menuitem">
		<span class="mdc-list-item__text">打开商品页面</span>
	</a>
	@endif
	@can('export', $product)
	<div class="mdc-menu-surface--anchor d-inline-block">
		<button type="button" class="mdc-button mdc-button--unelevated ml-2 open-menu-button">
			<span class="mdc-button__label">操作</span>
		</button>
		<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
				@if($product->product)
				<a href="{{ route('farfetch.unlink', ['farfetch_product' => $product,]) }}" class="mdc-list-item" role="menuitem">
					<span class="mdc-list-item__text">取消关联</span>
				</a>
				<a href="{{ route('farfetch.merge', ['farfetch_product' => $product, 'product' => $product->product,]) }}" class="mdc-list-item" role="menuitem">
					<span class="mdc-list-item__text">更新</span>
				</a>
				@else
				<a href="{{ route('farfetch.export', ['farfetch_product' => $product,]) }}" class="mdc-list-item" role="menuitem">
					<span class="mdc-list-item__text">上架新商品</span>
				</a>
				@foreach(\App\Product::where('designer_style_id', $product->designer_style_id)->where('brand_id', $product->designer->brand_id)->get() as $guess)
				<a href="{{ route('farfetch.merge', ['farfetch_product' => $product, 'product' => $guess,]) }}" class="mdc-list-item" role="menuitem">
					<span class="mdc-list-item__text">合并至{{ __($guess->color->name ?? '-') }}</span>
				</a>
				@endforeach
				@endif
			</ul>
		</div>
	</div>
	@elsecan
	@endcan
</div>
