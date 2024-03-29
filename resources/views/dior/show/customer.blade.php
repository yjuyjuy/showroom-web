<div>
	<a href="{{ $product->url }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank" rel="noreferrer">
		<span class="mdc-button__label">打开官网</span>
	</a>
	@if($product->product)
	<a href="{{ route('products.show', ['product' => $product->product,]) }}" class="mdc-button mdc-button--unelevated ml-2">
		<span class="mdc-button__label">查看报价</span>
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
				<a href="{{ route('dior.unlink', ['dior_product' => $product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
					<span class="mdc-list-item__text">取消关联</span>
				</a>
				<a href="{{ route('dior.merge', ['dior_product' => $product, 'product' => $product->product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
					<span class="mdc-list-item__text">更新</span>
				</a>
				@else
				<a href="{{ route('dior.export', ['dior_product' => $product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
					<span class="mdc-list-item__text">上架新商品</span>
				</a>
				@foreach(\App\Product::where('designer_style_id', $product->id)->where('brand_id', $product->brand_id)->get() as $guess)
				<a href="{{ route('dior.merge', ['dior_product' => $product, 'product' => $guess,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
					<span class="mdc-list-item__text">合并至{{ __($guess->color->name ?? '-') }}</span>
				</a>
				@endforeach
				@endif
			</ul>
		</div>
	</div>
	@endcan
</div>
