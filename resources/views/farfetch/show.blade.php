@extends('layouts.app')

@section('title', $product->displayName().' - Farfetch')

@section('content')
<div class="container">
	<div class="">
		<carousel :images='@json($product->images->pluck('url')->toArray())'></carousel>
	</div>

	<div class="container__content d-flex flex-column">
		<div class="d-flex flex-column products-show__info--properties">
			<div class="my-1">
				<a href="{{ route('farfetch.index',['designer' => [$product->designer->id]]) }}"
					 class="mdc-typography--headline6" style="text-transform: uppercase;">
					{{ $product->designer->description }}
				</a>

				<a href="{{ route('farfetch.index',['category' => [$product->category->id]]) }}"
					 class="mdc-typography--headline6">
					{{ $product->category->description }}
				</a>
			</div>
			<div class="my-1">
				<span class="mdc-typography--headline6" style="text-transform: capitalize;">
					{{ $product->short_description }}
				</span>

				<span class="mdc-typography--headline6" style="text-transform: capitalize;">
					{{ $product->colors }}
				</span>
			</div>
			<div class="my-1">
				<span>{{ $product->designer_style_id }}</span>
			</div>

			<div class="my-2"></div>

			@if($product->composition)
			<div class="my-1">
				<span>材质: {{ $product->composition }}</span>
			</div>
			@endif
			@if($product->model_is_wearing)
			<div class="my-1">
				<span>模特所穿尺码为{{ $product->model_is_wearing }}</span>
			</div>
			@endif
			@if($product->model_measurements)
			<div class="my-1">
				<span>模特资料: {{ $product->model_measurements }}</span>
			</div>
			@endif
		</div>

		<div class="d-flex flex-column products-show__info--customer">
			@if(!empty($product->size_price))
				@foreach($product->size_price as $size => $price)
				<span class="size-price">{{ $size }} - &yen;{{$price}}</span>
				@endforeach
			@else
				<span>{{ __('not available') }}</span>
			@endif
		</div>
		<div>
			<a href="{{ $product->url }}" class="ml-2 mdc-button mdc-button--unelevated" target="_blank">
				<span class="mdc-button__label">打开Farfetch</span>
			</a>
			@if($product->product)
			<a href="{{ route('products.show', ['product' => $product->product,]) }}" class="ml-2 mdc-button mdc-button--unelevated">
				<span class="mdc-button__label">打开Farfetch</span>
			</a>
			@endif
			@can('create', \App\Product::class)
			<div class="mdc-menu-surface--anchor d-inline-block">
				<button type="button" class="mdc-button mdc-button--unelevated ml-2 open-menu-button">
					<span class="mdc-button__label">操作</span>
				</button>
				<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
					<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
						@if($product->product)
						<a href="{{ route('farfetch.unlink', ['farfetch_product' => $product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
							<span class="mdc-list-item__text">取消关联</span>
						</a>
						<a href="{{ route('farfetch.merge', ['farfetch_product' => $product, 'product' => $product->product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
							<span class="mdc-list-item__text">更新</span>
						</a>
						@else
						<a href="{{ route('farfetch.export', ['farfetch_product' => $product,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
							<span class="mdc-list-item__text">上架新商品</span>
						</a>
						@foreach(\App\Product::where('designer_style_id', 'like', $product->designer_style_id.'%')->where('brand_id', $product->designer->mapped_id)->get() as $guess)
						<a href="{{ route('farfetch.merge', ['farfetch_product' => $product, 'product' => $guess,]) }}" class="mdc-list-item mdc-list-item__text" role="menuitem">
							<span class="mdc-list-item__text">合并至{{ __($guess->color->name ?? '-') }}</span>
						</a>
						@endforeach
						@endif
					</ul>
				</div>
			</div>
			@endcan
		</div>
		@if($product->product_measurements)
		<div class="text-left">
			<span>尺寸:</span><br>
			@foreach(explode("\n", $product->product_measurements) as $measurement)
				<span>{{ $measurement }}</span><br><br>
			@endforeach
		</div>
		@endif

	</div>
</div>
@include('layouts.back_fab')
@endsection
