<div class="">
	@auth
		@if($user->following_products->contains($product))
		<button type="button" class="mdc-button mdc-button--unelevated" onclick="follow('products',{{ $product->id }},false)">
			<span class="mdc-button__label">{{ __('following') }}</span>
		</button>
		@else
		<button type="button" class="mdc-button mdc-button--outlined" onclick="follow('products',{{ $product->id }})">
			<span class="mdc-button__label">{{ __('follow') }}</span>
		</button>
		@endif
	@else
	<button type="submit" class="ml-4 mdc-button mdc-button--unelevated" form="follow-product-form">
		<span class="mdc-button__label">{{ __('follow') }}</span>
	</button>
	<form id="follow-product-form" action="{{ route('follow.product', ['product' => $product,]) }}" method="post" style="display: none;">@csrf</form>
	@endauth

	@if($user && $user->is_reseller)
		<div class="d-inline-block ml-2">
			<input type="text" value="{{ $product->offers_to_string }}" style="opacity:0;position:absolute;left:-100%;">
			<button type="button" class="mdc-button mdc-button--unelevated" onclick="var input = this.parentElement.firstChild;input.focus();input.setSelectionRange(0,input.value.length);document.execCommand('copy');this.firstChild.textContent='复制成功!';input.blur();">
				<span class="mdc-button__label">复制尺码价格</span>
			</button>
		</div>
	@endif
</div>
