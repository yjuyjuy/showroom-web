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
		<span class="mdc-button__label">{{ __('Link to page') }}</span>
	</a>
	@if(auth()->user()->isSuperAdmin())
	<?php $guesses = \App\Product::where('designer_style_id', $product->designer_style_id)->where('brand_id', $product->designer->brand_id)->get(); ?>
		@if($guesses->isEmpty())
			<a href="{{ route('farfetch.export', ['farfetch_product' => $product,]) }}" class="ml-2 mdc-button mdc-button--unelevated">
				<span class="mdc-button__label">上架</span>
			</a>
		@else
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button mdc-button--unelevated ml-2 open-menu-button">
				<span class="mdc-button__label">选择</span>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
				<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					<a class="mdc-list-item" role="menuitem" href="{{ route('farfetch.export', ['farfetch_product' => $product,]) }}">
						<span class="mdc-list-item__text">上架新商品</span>
					</a>
					@foreach($guesses as $guess)
					<a href="{{ route('farfetch.export', ['farfetch_product' => $product, 'product' => $guess,]) }}" class="mdc-list-item" role="menuitem">
						<span class="mdc-list-item__text">导入-{{ __($guess->color->name) }}</span>
					</a>
					@endforeach
				</ul>
			</div>
		</div>
		@endif
	@endif
</div>
