@extends('layouts.app')
@section('title', '图片管理 - '.$product->displayName())
@section('content')
<div class="px-2" style="position:relative;">
	<div class="d-flex">
		<div class="d-flex flex-wrap align-content-start">
			<div class="mdc-menu-surface--anchor">
				<button type="button" class="mdc-button open-menu-button">
					<span class="mdc-button__label">{{ __('add price') }}</span>
					<i class="material-icons mdc-button__icon" aria-hidden="true">arrow_drop_down</i>
				</button>
				<div class="mdc-menu mdc-menu-surface mdc-menu--with-button">
					<ul class="mdc-list" role="menu" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
						@foreach(\App\Vendor::whereNotIn('id',$product->prices->pluck('vendor_id')->toArray())->get() as $vendor)
						<li class="mdc-list-item" role="menuitem">
							<a href="{{route('prices.create',['product' => $product, 'vendor' => $vendor])}}" class="mdc-list-item__text w-100 text-left" onclick="event.preventDefault(); window.location.replace(this.href);">{{$vendor->name}}</a>
						</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		<div class="d-flex flex-grow-1 justify-content-end text-right">
			@include('products.show.properties')
		</div>
	</div>
	<div class="row">
		@foreach(range(1,max($images->max('order') + 1, 8)) as $order)
		@forelse($images->where('order',$order) as $image)
		<div class="col-6 col-md-3 pb-3">
			<image-item src="{{$image->url}}" id="{{$image->id}}"></image-item>
			<span>#{{ $order }}</span>
		</div>
		@empty
		<div class="col-6 col-md-3 pb-3">
			<empty-image product-id="{{$product->id}}" order="{{ $order }}"></empty-image>
			<span>#{{ $order }}</span>
		</div>
		@endforelse
		@endforeach
	</div>
</div>
@endsection
