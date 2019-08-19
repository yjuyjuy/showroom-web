<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->getSizePrice('retail') as $size => $data)
	<span class="size-price">{{ $size }} - &yen;{{$data['price']}} - {{$data['retailer']}}</span>
	@empty
	<span class="not-available">{{ __('not available') }}</span>
	@endforelse
</div>
@if($product->retails->where('link')->isNotEmpty())
<div class="d-flex flex-column products-show__info--link">
	<div class="w-100 d-block">
		<div class="mdc-menu-surface--anchor d-inline-block">
			<button type="button" name="button" class="mdc-button open-menu-button mdc-button--unelevated">
				<span class="mdc-button__label">{{ __('Where to buy') }}</span>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
			    @foreach($product->retails->where('link') as $retail)
					<li class="mdc-list-item" role="menuitem">
			      <a href="{{ $retail->link }}" class="mdc-list-item__text w-100 text-left" target="_blank">{{ __($retail->retailer->name) }} - &yen;{{ min($retail->prices) }}</a>
			    </li>
					@endforeach
			  </ul>
			</div>
		</div>
	</div>
</div>
@endif
