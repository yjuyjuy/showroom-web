@extends('layouts.app')

@section('title', $vendor->name.'的所有商品')

@section('content')
<div id="products-index" class="">
	@include('vendor.banner')
	@if($products->isEmpty())
		<div class="my-5 text-center">
			没有搜索到相关商品
		</div>
	@else
	<ul class="mdc-image-list main-image-list">
		@foreach($products as $product)
		<li class="mdc-image-list__item">
			<a href="{{ route('vendor.products.show',['vendor' => $vendor, 'product' => $product ]) }}">
				<div class="">
					<img class="mdc-image-list__image lazy" data-src="{{$product->image->small ?? ''}}">
				</div>
				<div class="mdc-image-list__supporting">
					<span class="mdc-image-list__label brand">{{ $product->brand->name }}</span>
					<span class="mdc-image-list__label product-name">{{ $product->name_cn }}</span>
					@if($product->getMinOffer())
					<span class="mdc-image-list__label">
						{{ "\u{00a5}".$product->getMinOffer() }}
					</span>
					@else
					<span class="mdc-image-list__label mdc-theme--primary">
						{{ __('not available') }}
					</span>
					@endif
				</div>
			</a>
		</li>
		@endforeach
	</ul>
	@include('layouts.pages')
	<button id="display-options-fab" class="mdc-fab" aria-label="display options">
	  <span class="mdc-fab__icon material-icons">filter_list</span>
	</button>
	<div id="display-options-dialog"
		 class="mdc-dialog"
     role="alertdialog"
     aria-modal="true"
     aria-labelledby="my-dialog-title"
     aria-describedby="my-dialog-content">
	  <div class="mdc-dialog__container">
	    <div class="mdc-dialog__surface">
	      <div class="mdc-dialog__content" id="my-dialog-content">
					<form target="_self" id="display-option-form">
						<div id="list-group" class="mdc-list-group">
							@include('products.index.sortList')
							@include('products.index.filterList')
						</div>
					</form>
	      </div>

	      <footer class="mdc-dialog__actions">
	        <button type="button" class="mdc-button mdc-button--error mdc-dialog__button" data-mdc-dialog-action="close">
	          <span class="mdc-button__label">{{ __('close') }}</span>
	        </button>
					<button type="button" class="mdc-button mdc-dialog__button"
									onclick="[].map.call(document.getElementById('display-option-form').elements,function(el) { el.checked = false; el.selected = false;});">
	          <span class="mdc-button__label">{{ __('clear') }}</span>
	        </button>
	        <button type="submit" class="mdc-button mdc-button--unelevated mdc-dialog__button" data-mdc-dialog-action="accept" form="display-option-form">
	          <span class="mdc-button__label">{{ __('apply') }}</span>
	        </button>
	      </footer>
	    </div>
	  </div>
	  <div class="mdc-dialog__scrim"></div>
	</div>
	@endif
</div>
@endsection
