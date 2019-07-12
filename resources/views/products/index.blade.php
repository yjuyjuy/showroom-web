@extends('layouts.app')
@section('title','所有商品 - TheShowroom')
@section('content')
<div class="pt-2">
	<ul class="mdc-image-list my-image-list">
		@foreach($products as $product)
		<li class="mdc-image-list__item">
			<div class="">
				<img class="mdc-image-list__image" src="{{$product->images->first()->url ?? asset('storage/icons/ImagePlaceholder.svg')}}">
			</div>
			<div class="mdc-image-list__supporting">
				<span class="mdc-image-list__label brand">{{ $product->brand->name }}</span>
				<span class="mdc-image-list__label">{{ $product->name_cn }}</span>
				@if($product->price)
				<span class="mdc-image-list__label">
					{{ "\u{00a5}".$product->price }}
				</span>
				@else
				<span class="mdc-image-list__label">
					缺货
				</span>
				@endif
			</div>
		</li>
		@endforeach
	</ul>
	<button id="open-options-fab" class="mdc-fab" aria-label="Favorite">
	  <span class="mdc-fab__icon material-icons">favorite</span>
	</button>
	<div id="display-options-dialog"
		 class="mdc-dialog"
     role="alertdialog"
     aria-modal="true"
     aria-labelledby="my-dialog-title"
     aria-describedby="my-dialog-content">
	  <div class="mdc-dialog__container">
	    <div class="mdc-dialog__surface">
	      <!-- Title cannot contain leading whitespace due to mdc-typography-baseline-top() -->
	      <h2 class="mdc-dialog__title" id="my-dialog-title"><!--
	     -->display options<!--
	   --></h2>
	      <div class="mdc-dialog__content" id="my-dialog-content">
					<div id="list-group" class="mdc-list-group">
						@include('products.index.sortList')
						@include('products.index.filterList')
					</div>
	      </div>

	      <footer class="mdc-dialog__actions">
	        <button id="mdc-dialog__cancel-button" type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="close">
	          <span class="mdc-button__label">Cancel</span>
	        </button>
	        <button id="mdc-dialog__accept-button" type="button" class="mdc-button mdc-button--unelevated mdc-dialog__button" data-mdc-dialog-action="accept">
	          <span class="mdc-button__label">OK</span>
	        </button>
	      </footer>
	    </div>
	  </div>
	  <div class="mdc-dialog__scrim"></div>
	</div>
</div>
@endsection
