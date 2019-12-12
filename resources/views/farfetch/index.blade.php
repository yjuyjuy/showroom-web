@extends('layouts.app')

@section('title')
@if ($designer) {{ $designer->description.' - Farfetch' }}
@elseif ($category) {{ $category->description.' - Farfetch' }}
@else {{ 'Farfetch' }} @endif
@endsection

@section('content')
<div id="products-index" class="">
	@php $retailer = \App\Retailer::find(1467053076); @endphp
	@include('retailer.banner')
	<div class="d-flex">
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>{{ __('designer') }}</span></button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@if(old('designer'))
					<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="input[name='designer[]']" onclick="
						event.preventDefault();
						var target = document.querySelector(this.dataset.target);
						[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
						target.form.submit();">所有设计师</a>
					@endif
					@foreach($filters['designer'] as $value)
						<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="#filter-designer-{{$value->url_token}}-checkbox" onclick="
							event.preventDefault();
							var target = document.querySelector(this.dataset.target);
							[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
							target.checked = true;
							target.form.submit();">{{ __($value->description) }}</a>
					@endforeach
			  </ul>
			</div>
		</div>
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>{{ __('category') }}</span></button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
			  <ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@if(old('category'))
					<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="input[name='category[]']" onclick="
						event.preventDefault();
						var target = document.querySelector(this.dataset.target);
						[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
						target.form.submit();">所有分类</a>
					@endif
					@foreach($filters['category'] as $value)
						<a href="#" class="mdc-list-item mdc-list-item__text" role="menuitem" data-target="#filter-category-{{$value->url_token}}-checkbox" onclick="
							event.preventDefault();
							var target = document.querySelector(this.dataset.target);
							[].map.call(document.querySelectorAll('input[name=\'' + target.name + '\']'), (el)=>{el.checked=false;});
							target.checked = true;
							target.form.submit();">{{ __($value->description) }}</a>
					@endforeach
			  </ul>
			</div>
		</div>
		<div class="my-auto" style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
			@if(old('designer') || old('category'))
			<span>已选:</span>&nbsp;
				@if(old('designer'))
				<span>{{ implode(', ',$filters['designer']->whereIn('id', old('designer'))->pluck('description')->toArray()) }}</span>&nbsp;
				@endif
				@if(old('category'))
				<span>{{ implode(', ', array_map('__', $filters['category']->whereIn('id', old('category'))->pluck('description')->toArray())) }}</span>&nbsp;
				@endif
			@endif
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
					<span class="mdc-image-list__label brand">{{ $product->designer->description ?? '' }}</span>
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
