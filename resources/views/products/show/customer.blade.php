<div class="d-flex flex-column products-show__info--customer">
	@forelse($product->getSizePrice('retail') as $size => $data)
	<div><span>{{ $size }} - &yen;{{$data['price']}} - {{$data['retailer']}}</span></div>
	@empty
	<span>{{ __('not available') }}</span>
	@endforelse
</div>
@if(!empty($product->links))
<div class="d-flex justify-content-end">
	<div class="mdc-menu-surface--anchor">
		<button type="button" class="mdc-button open-menu-button">
			<span class="mdc-button__label">更多详情</span>
			<i class="material-icons mdc-button__icon" aria-hidden="true">arrow_drop_down</i>
		</button>
		<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
				@foreach($product->links as $description => $url)
					<a href="{{ $url }}" class="mdc-list-item mdc-list-item__text text-left" role="menuitem">{{ $description }}</a>
				@endforeach
			</ul>
		</div>
	</div>
</div>
@endif
