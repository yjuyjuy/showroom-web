<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->getSizePrice('retail') as $size => $data)
	<div><a href="{{ $data['link'] ?? '#'}}" class="size-price">{{ $size }} - &yen;{{$data['price']}} - {{$data['retailer']}}</a></div>
	@empty
	<span class="not-available">{{ __('not available') }}</span>
	@endforelse
</div>
<div class="">
	@if(auth()->user()->following_products->contains($product))
	<button type="button" name="button" class="mdc-button mdc-button--error" onclick="unfollow_product({{ $product->id }})">
		<span class="mdc-button__label">{{ __('unfollow') }}</span>
	</button>
	@else
	<button type="button" name="button" class="mdc-button mdc-button--unelevated" onclick="follow_product({{ $product->id }})">
		<span class="mdc-button__label">{{ __('follow') }}</span>
	</button>
	@endif
	<?php $retails_with_links = $product->retails->where('link'); ?>
	@if($retails_with_links->isNotEmpty())
		@if($retails_with_links->count() > 1)
		<div class="mdc-menu-surface--anchor d-inline-block">
			<button type="button" name="button" class="ml-2 mdc-button open-menu-button mdc-button--unelevated">
				<span class="mdc-button__label">{{ __('Where to buy') }}</span>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
			    @foreach($retails_with_links as $retail)
					<li class="mdc-list-item" role="menuitem">
			      <a href="{{ $retail->link }}" class="mdc-list-item__text w-100 text-left" target="_blank">{{ __($retail->retailer->name) }} - &yen;{{ min($retail->prices) }}</a>
			    </li>
					@endforeach
			  </ul>
			</div>
		</div>
		@else
		<a href="{{$retails_with_links->first()->link}}" class="ml-2 mdc-button mdc-button--unelevated">
			<span class="mdc-button__label">{{ __('Where to buy') }}</span>
		</a>
		@endif
	@endif
</div>
