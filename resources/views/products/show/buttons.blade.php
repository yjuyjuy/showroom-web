<div class="">
	@if(auth()->user()->following_products->contains($product))
	<button type="button" class="mdc-button mdc-button--error" onclick="unfollow_product({{ $product->id }})">
		<span class="mdc-button__label">{{ __('following') }}</span>
	</button>
	@else
	<button type="button" class="mdc-button mdc-button--unelevated" onclick="follow_product({{ $product->id }})">
		<span class="mdc-button__label">{{ __('follow') }}</span>
	</button>
	@endif
	@if(Route::currentRouteName() != 'vendor.products.show')
		@if($product->retails->count() > 1)
		<div class="mdc-menu-surface--anchor d-inline-block">
			<button type="button" class="ml-2 mdc-button open-menu-button mdc-button--unelevated">
				<span class="mdc-button__label">{{ __('Contact retailer') }}</span>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
				<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@foreach($product->retails as $retail)
					<li class="mdc-list-item" role="menuitem">
						@if($retail->link)
						<a href="{{ $retail->link }}" class="mdc-list-item__text w-100 text-left" target="_blank">{{ $retail->retailer->name.' - '.implode('/', array_keys($retail->prices)) }}</a>
						@else
						<a href="#" onclick="open_wechat('{{ $retail->retailer->name }}')" class="mdc-list-item__text w-100 text-left">{{ $retail->retailer->name.' - '.implode('/', array_keys($retail->prices)) }}</a>
						@endif
					</li>
					@endforeach
				</ul>
			</div>
		</div>
		@elseif($retail = $product->retails->first())
			@if($retail->link)
				<a href="{{ $retail->link }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank">
			@else
				<a href="#" onclick="open_wechat('{{ $retail->retailer->name }}')" class="ml-2 mdc-button mdc-button--unelevated">
			@endif
				<span class="mdc-button__label">{{ __('Contact retailer') }}</span>
			</a>
		@endif
	@endif
	@if($user->is_reseller)
		@if($product->offers->count() > 1)
		<div class="mdc-menu-surface--anchor d-inline-block">
			<button type="button" class="ml-2 mdc-button open-menu-button mdc-button--unelevated">
				<span class="mdc-button__label">{{ __('Contact vendor') }}</span>
			</button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
				<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@foreach($product->offers as $offer)
					<li class="mdc-list-item" role="menuitem">
						@if($offer->link)
						<a href="{{ $offer->link }}" class="mdc-list-item__text w-100 text-left" target="_blank">{{ $offer->vendor->name.' - '.implode('/', array_keys($offer->prices)) }}</a>
						@else
						<a href="#" onclick="open_wechat('{{ $offer->vendor->name }}')" class="mdc-list-item__text w-100 text-left">{{ $offer->vendor->name.' - '.implode('/', array_keys($offer->prices)) }}</a>
						@endif
					</li>
					@endforeach
				</ul>
			</div>
		</div>
		@elseif($offer = $product->offers->first())
			@if($offer->link)
				<a href="{{ $offer->link }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank">
			@else
				<a href="#" onclick="open_wechat('{{ $offer->vendor->name }}')" class="ml-2 mdc-button mdc-button--unelevated">
			@endif
				<span class="mdc-button__label">{{ __('Contact vendor') }}</span>
			</a>
		@endif
	@endif
</div>
