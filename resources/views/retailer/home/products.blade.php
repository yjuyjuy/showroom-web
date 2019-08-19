<div class="">
	<div class="">关联商品</div>
	<div class="d-flex flex-column pr-2" style="max-height: 80vh; overflow: auto;">
	<?php $retail = $retailer->retails->first();
				$retail->title = "【Dope】OFF WHITE C/O VIRGIL ABLOH 19fw 女王油画涂鸦logo短袖 + 黑色 偏大4码, 不退";
				$retail->link = 'https://item.taobao.com/item.htm?spm=a1z10.3-c.w4002-7908580240.31.220b1e9aXW5UGN&id=597832916815'; ?>


@foreach([1,2,3,4,5,6,7,8,9,10] as $i)
			<div class="product-card w-100">
				<div class="product-card__header d-flex justify-content-end">
					<a href="#" onclick="event.preventDefault();">&times;</a>
				</div>
				<div class="product-card__content d-flex">
					<div class="product-card__content--text d-flex flex-column p-2">
						<div class="product-card__title1 my-2">
							<a href="{{ $retail->link }}">{{ $retail->title }}</a>
						</div>
						<div class="product-card__title2 my-2">
							<div class="mdc-text-field mdc-text-field--outlined w-100">
							  <input class="mdc-text-field__input" id="text-field-hero-input" name="product">
							  <div class="mdc-notched-outline">
							    <div class="mdc-notched-outline__leading"></div>
							    <div class="mdc-notched-outline__notch">
							      <label for="text-field-hero-input" class="mdc-floating-label">{{ __('search') }}</label>
							    </div>
							    <div class="mdc-notched-outline__trailing"></div>
							  </div>
							</div>
						</div>
						<div class="product-card__selector my-2 d-flex justify-content-between align-items-center">
							<div class="">
								<span>ID: {{ $retail->product->id }}</span>
							</div>
							<div class="">
								<button type="button" class="mdc-button mdc-button--unelevated">{{ __('submit') }}</button>
							</div>
						</div>
					</div>
					<div class="product-card__content--image px-3 my-auto" style="width: 30%;">
						<img src="{{ $retail->product->image->url }}" alt="" class="w-100">
					</div>
				</div>
			</div>
@endforeach


	</div>
</div>
