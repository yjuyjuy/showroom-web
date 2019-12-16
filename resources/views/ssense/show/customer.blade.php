<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->size_price as $size => $price)
	<span class="size-price">{{ $size }} - &yen;{{$price}}</span>
	@empty
	<span>{{ __('not available') }}</span>
	@endforelse
</div>
<div>
	<a href="{{ $product->url }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank">
		<span class="mdc-button__label">打开SSENSE</span>
	</a>
	@if($product->product)
	<a href="{{ route('products.show', ['product' => $product->product,]) }}" class="ml-2 mdc-button mdc-button--unelevated">
		<span class="mdc-button__label">打开商品页面</span>
	</a>
	@endif
	@can('create', \App\Product::class)
	<div class="mdc-menu-surface--anchor d-inline-block">
		<button type="button" class="mdc-button mdc-button--unelevated ml-2 open-menu-button">
			<span class="mdc-button__label">操作</span>
		</button>
		<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
				@if($product->product)
				<a href="{{ route('ssense.unlink', ['ssense_product' => $product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
					<span class="mdc-list-item__text">取消关联</span>
				</a>
				<a href="{{ route('ssense.merge', ['ssense_product' => $product, 'product' => $product->product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
					<span class="mdc-list-item__text">更新</span>
				</a>
				@else
				<a href="{{ route('ssense.export', ['ssense_product' => $product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
					<span class="mdc-list-item__text">上架新商品</span>
				</a>
				@endif
			</ul>
		</div>
	</div>
	@endcan
</div>
