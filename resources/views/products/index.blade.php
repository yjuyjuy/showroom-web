@extends('layouts.app')
@section('title','所有商品 - TheShowroom')
@section('content')
<div class="container">
	<form action="{{ url()->current() }}" class="row">

		<div class="col order-md-2">
			<ul class="mdc-image-list my-image-list">
				@foreach([0,1,2,3,4,5] as $value)
				@foreach($products as $product)
				<li class="mdc-image-list__item">
					<div class="">
						<img class="mdc-image-list__image" src="{{$product->images->first()->url ?? asset('storage/icons/ImagePlaceholder.svg')}}">
					</div>
					<div class="mdc-image-list__supporting">
						<span class="mdc-image-list__label">{{ $product->brand->name }}</span>
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
				@endforeach
			</ul>
			<!-- <div class="row">
				@forelse($products as $product)
				<div class="product col-6 col-md-4 col-xl-3 align-items-center mb-2">
					<div class="image">
						<a href="{{ route('products.show',['product' => $product->id ]) }}">
							<img class="w-100" src="{{$product->images->first()->url ?? asset('storage/icons/ImagePlaceholder.svg')}}">
						</a>
					</div>
					<div class="text mt-n4">
						<div class="">
							<div class="brand text-left ">
								<a href="{{ route('products.show',['product' => $product->id ]) }}" class="text-decoration-none">{{ $product->brand->name }}</a>
							</div>
							<div class="name">
								<a href="{{ route('products.show',['product' => $product->id ]) }}" class="text-decoration-none">{{ $product->name_cn }}</a>
							</div>
							<div class="price">
								@if($product->price)
								<a href="{{ route('products.show',['product' => $product ]) }}" class="">
									{{ "\u{00a5}".$product->price }}
								</a>
								@else
								<a href="{{ route('products.show',['product' => $product ]) }}" class="text-primary">
									缺货
								</a>
								@endif
							</div>
						</div>
					</div>
				</div>
				@empty
				<div class="">
					no result
				</div>
				@endforelse
			</div> -->

		</div>

		<div class="col-md-auto order-md-1">
			<a id="open-sort" class="text-primary open-overlay" href="#" @click="$refs.sort.classList.toggle('open');">Sort</a>
			<div id="sort" ref="sort" class="form-group overlay">
				<a class="close-overlay" href="#" @click="$refs.sort.classList.remove('open');">&times;</a>
				@foreach($sortMethods as $method)
				<div class="form-check">
					<input class="form-check-input" type="radio" name="sort" value="{{ $method['name'] }}" id="{{ $method['name'] }}" onchange="submit()" {{ (old('sort')==$method['name']) || (!old('sort') && $method['name']=='default')?' checked':'' }}>
					<label class="form-check-label" for="{{ $method['name'] }}">{{ $method['name_cn'] }}</label>
				</div>
				@endforeach
			</div>
			<div class="w-100"></div>
			<a id="open-filter" class="text-primary open-overlay" href="#" @click="$refs.filter.classList.toggle('open');">Filter</a>
			<div id="filter" ref="filter" class="overlay">
				<a class="close-overlay" href="#" @click="$refs.filter.classList.remove('open');">&times;</a>
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="show_available_only" value="show_available_only" id="show_available_only" onchange="submit()" {{ old("show_available_only")? 'checked':'' }}>
					<label class="form-check-label" for="show_available_only">只显示有货</label>
				</div>
				<div class="w-100"></div>
				@if(($user = auth()->user()) && $user->vendor)
				<div class="form-check">
					<input class="form-check-input" type="checkbox" name="show_vendor_only" value="{{$user->vendor->id}}" id="show_vendor_only" onchange="submit()" {{ old("show_vendor_only")? 'checked':'' }}>
					<label class="form-check-label" for="show_vendor_only">我的库存</label>
				</div>
				<div class="w-100"></div>

				@if($user->isSuperAdmin())
				<a class="" data-toggle="collapse" href="#vendor-group">货源</a>
				<div class="collapse" id="vendor-group">
					@foreach(\App\Vendor::all() as $vendor)
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="vendor[]" value="{{$vendor->id}}" id="vendor-{{$vendor->id}}" onchange="submit()" {{ (old('vendor') && in_array($vendor->id, old('vendor')))?' checked':'' }}>
						<label class="form-check-label" for="vendor-{{$vendor->id}}">{{$vendor->name}}</label>
					</div>
					@endforeach
				</div>
				<div class=""></div>
				@endif
				@endif

				@foreach(["category" => App\Category::all(),"color" => App\Color::all(),"season" => App\Season::all(),"brand" => App\Brand::all()] as $key => $values)
				<a class="" data-toggle="collapse" href="#{{$key}}-group">{{$key}}</a>
				<div class="collapse show" id="{{$key}}-group">
					@foreach($values as $value)
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="{{$key}}[]" value="{{$value->id}}" id="{{$key}}-{{$value->id}}" onchange="submit()" {{ (old($key) && in_array($value->id, old($key)))?' checked':'' }}>
						<label class="form-check-label" for="{{$key}}-{{$value->id}}">{{$value->name}}</label>
					</div>
					@endforeach
				</div>
				<div class=""></div>
				@endforeach
			</div>

		</div>

	</form>
</div>
@endsection
