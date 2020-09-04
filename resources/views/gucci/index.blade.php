@extends('layouts.app')

@section('title')
@if ($category) {{ implode(' - ', array_map('__', explode('-', $category))).' - Gucci' }}
@else {{ 'Gucci' }} @endif
@endsection

@section('content')
<div id="products-index" class="">
	<div class="d-flex">
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>
				@if($category)
					@if($category->parent)
					{{ $category->parent->translated_description }}
					@else
					{{ $category->translated_description }}
					@endif
				@else
				{{ __('category') }}
				@endif</span></button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
				<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@if($category)
					<a class="mdc-list-item mdc-list-item__text" role="menuitem"
						href="{{ route('gucci.index') }}">{{ __('all categories') }}</a>
					@endif
					@foreach(\App\GucciCategory::whereNull('parent_id')->get() as $main_category)
					<a class="mdc-list-item mdc-list-item__text" role="menuitem"
						href="{{ route('gucci.categories.index', ['category' => $main_category,]) }}">
						{{ implode(' - ', array_map('__', explode('-', $main_category->translated_description ?? 'uncategorized'))) }}</a>
					@endforeach </ul>
			</div>
		</div>
		@if($category)
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button"><span class='mdc-button__label'>
				@if($category->parent)
				{{ $category->translated_description }}
				@else
				{{ __('subcategory') }}
				@endif</span></button>
			<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
				<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@if($category->parent)
					<a class="mdc-list-item mdc-list-item__text" role="menuitem"
						href="{{ route('gucci.categories.index', ['category' => $category->parent,]) }}">
						{{ __('all subcategories') }}</a>
					@endif
					@foreach(\App\GucciCategory::where('parent_id', $category->parent_id ?? $category->id)->get() as $sub_category) 
					<a class="mdc-list-item mdc-list-item__text" role="menuitem"
						href="{{ route('gucci.categories.index', ['category' => $sub_category,]) }}">
						{{ implode(' - ', array_map('__', explode('-', $sub_category->translated_description))) }}</a>
					@endforeach </ul>
			</div>
		</div>
		@endif
	</div>
	@if($products->isEmpty())
		<div class="my-5 text-center">
			没有搜索到相关商品
		</div>
	@else
	<ul class="mdc-image-list main-image-list">
		@foreach($products->load('image') as $product)
		<li class="mdc-image-list__item">
			<a href="{{ route('gucci.show',['product' => $product,]) }}">
				<div class="">
					<img class="mdc-image-list__image lazy" data-src="{{ $product->image->url ?? '' }}">
				</div>
				<div class="mdc-image-list__supporting">
					<span class="mdc-image-list__label brand">Gucci</span>
					<span class="mdc-image-list__label product-name">{{ $product->name }}</span>
				</div>
			</a>
		</li>
		@endforeach
	</ul>
	@include('layouts.pages')
	@endif
@endsection
