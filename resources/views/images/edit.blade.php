@extends('layouts.app')
@section('title', '图片管理 - '.$product->displayName())
@section('content')
<div class="px-2" style="position:relative;">
	<div class="d-flex" style="position:absolute; top:0; left:15px; z-index:1;">
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button">
				<span class="mdc-button__label">{{ __('more websites') }}</span>
			</button>
			<div class="mdc-menu mdc-menu--with-button mdc-menu-surface" style="z-index:1;">
			  <ul class="mdc-list" role="group" aria-hidden="true" aria-orientation="vertical" tabindex="-1">
					@foreach($websites->whereNotIn('id',$images->keys()) as $website)
			    <li class="mdc-list-item" role="checkbox" aria-checked="false">
						<span class="mdc-list-item__graphic">
				      <div class="mdc-checkbox">
				        <input type="checkbox" value="{{$website->id}}" class="mdc-checkbox__native-control" id="website{{$website->id}}-checkbox"
											 onchange="document.querySelector('#website' + this.value).classList.toggle('show',this.checked);"/>
				        <div class="mdc-checkbox__background">
				          <svg class="mdc-checkbox__checkmark"
				                viewBox="0 0 24 24">
				            <path class="mdc-checkbox__checkmark-path"
				                  fill="none"
				                  d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
				          </svg>
				          <div class="mdc-checkbox__mixedmark"></div>
				        </div>
				      </div>
				    </span>
				    <label class="mdc-list-item__text" for="website{{$website->id}}-checkbox">{{ $website->name }}</label>
			    </li>
					@endforeach
			  </ul>
			</div>
		</div>
		<div class="mdc-menu-surface--anchor">
			<button type="button" class="mdc-button open-menu-button ml-2">
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
	<div class="d-flex justify-content-end text-right">
		@include('products.show.properties')
	</div>
	@foreach($images as $website_id => $website_images)
	<div id="website{{$website_id}}" class="row">
		<div class="col-12 h3 text-center">{{$websites->firstWhere('id',$website_id)->name}}</div>
		@foreach(range(1,max($website_images->max('order') + 1, 8)) as $order)
		<div class="col-6 col-md-3 pb-3">
			@if($image = $website_images->firstWhere('order',$order))
			<image-item src="{{$image->url}}" id="{{$image->id}}"></image-item>
			@else
			<empty-image product-id="{{$product->id}}" website-id="{{$website_id}}" order="{{ $order }}"></empty-image>
			@endif
			<span>#{{ $order }}</span>
		</div>
		@endforeach
	</div>
	@endforeach

	@foreach($websites->whereNotIn('id',$images->keys()) as $website)
	<div id="website{{$website->id}}" class="row website-empty">
		<div class="col-12 h3 text-center">{{$website->name}}</div>
		@foreach(range(1,8) as $order)
		<div class="col-6 col-md-3 pb-3">
			<empty-image product-id="{{$product->id}}" website-id="{{$website->id}}" order="{{ $order }}"></empty-image>
			<span>#{{ $order }}</span>
		</div>
		@endforeach
	</div>
	@endforeach
</div>
@endsection
