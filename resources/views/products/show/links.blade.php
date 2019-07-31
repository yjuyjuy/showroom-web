@if($prices_with_url = $product->prices->where('url')->isNotEmpty())
<div class="d-flex flex-column products-show__info--link">
	<div class="w-100 d-block">
		<div class="mdc-menu-surface--anchor d-inline-block">
			<button type="button" name="button" class="mdc-button open-menu-button mdc-button--unelevated">
				<span class="mdc-button__label">{{ __('Where to buy') }}</span>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
			    @foreach($product->prices->where('url') as $price)
					<li class="mdc-list-item" role="menuitem">
			      <a href="{{ $price->url }}" class="mdc-list-item__text w-100 text-left" target="_blank">{{ __($price->vendor->name) }}</a>
			    </li>
					@endforeach
			  </ul>
			</div>
		</div>
	</div>
</div>
@endif
