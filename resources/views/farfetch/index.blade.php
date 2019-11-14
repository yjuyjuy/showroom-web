@extends('layouts.app')

@section('title')
@if ($designer) {{ $designer->description.' - Farfetch' }}
@elseif ($category) {{ $category->description.' - Farfetch' }}
@else {{ 'Farfetch' }} @endif
@endsection

@section('content')
<div id="products-index" class="">
	<div class="d-flex">
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>{{ __('category') }}</span></button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@foreach($categories as $value)
						<a class="mdc-list-item mdc-list-item__text" role="menuitem" href="{{ route('farfetch.categories.index', ['category' => $value,]) }}">{{ __($value->description) }}</a>
				    </a>
					@endforeach
			  </ul>
			</div>
		</div>
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>{{ __('designer') }}</span></button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@foreach($designers as $value)
						<a class="mdc-list-item mdc-list-item__text" role="menuitem" href="{{ route('farfetch.designers.index', ['designer' => $value,]) }}">{{ __($value->description) }}</a>
					@endforeach
			  </ul>
			</div>
		</div>
	</div>
	@if($products->isEmpty())
		<div class="my-5 text-center">
			没有搜索到相关商品
		</div>
	@else
	<ul class="mdc-image-list main-image-list">
		@foreach($products->load('image') as $product)
		<li class="mdc-image-list__item">
			<a href="{{ route('farfetch.show',['product' => $product ]) }}">
				<div class="">
					<img class="mdc-image-list__image lazy" data-src="{{ $product->image->url ?? '' }}">
				</div>
				<div class="mdc-image-list__supporting">
					<span class="mdc-image-list__label brand">{{ $product->designer->description }}</span>
					<span class="mdc-image-list__label product-name">{{ $product->short_description }}</span>
					@if($product->price)
					<span class="mdc-image-list__label">
						{{ "\u{00a5}".$product->price }}
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
	@endif
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
							@include('farfetch.index.sort')
							@include('farfetch.index.filter')
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
</div>
@endsection
