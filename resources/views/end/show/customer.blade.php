<div class="d-flex flex-column products-show__info--customer">
	@if($product->sizes)
		@foreach(explode(',',$product->sizes) as $size)
		<span class="size-price">{{ $size }} - &yen;{{ $product->price }}</span>
		@endforeach
	@else
	<span>{{ __('not available') }}</span>
	@endif
</div>
<div>
	<a href="{{ $product->url }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank">
		<span class="mdc-button__label">{{ __('Link to page') }}</span>
	</a>
	@if(auth()->user()->isSuperAdmin())
	<?php $guesses = \App\Product::where('designer_style_id', $product->sku)->where('brand_id', $product->brand->id)->get(); ?>
		@if($guesses->isEmpty())
			<a href="{{ route('end.export', ['end_product' => $product,]) }}" class="ml-2 mdc-button mdc-button--unelevated">
				<span class="mdc-button__label">上架</span>
			</a>
		@else
		<div class="mdc-menu-surface--anchor d-inline">
			<button type="button" class="mdc-button mdc-button--unelevated ml-2 open-menu-button">
				<span class="mdc-button__label">选择</span>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
				<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					<a class="mdc-list-item mdc-list-item__text" role="menuitem" href="{{ route('end.export', ['end_product' => $product,]) }}">
						<span class="mdc-list-item__text">上架新商品</span>
					</a>
					@foreach($guesses as $guess)
					<a href="{{ route('end.export', ['end_product' => $product, 'product' => $guess,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
						<span class="mdc-list-item__text">导入-{{ __($guess->color->name ?? '') }}</span>
					</a>
					@endforeach
				</ul>
			</div>
		</div>
		@endif
	@endif
</div>
