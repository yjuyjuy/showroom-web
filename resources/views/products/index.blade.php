@extends('layouts.index')

@section('title','所有商品 - TheShowroom')

@section('left-aside')
@include('products.availability')
@include('products.filters')
@endsection

@section('center')

<?php $targetRouteName = Str::replaceLast('index', 'show', Route::currentRouteName()); ?>
@foreach($products as $product)
<div class="product col-6 col-md-3 flex-column align-items-center">

	<div class="image">
		<a href="{{ route($targetRouteName,['product' => $product->id ]) }}">
			<img class="w-100" src="/storage/images/{{ $product->images->first()->filename ?? '1101182005_1_6.jpg' }}">
		</a>
	</div>

	<div class="text mt-n4">
		<div class="flex-column text-center">

			<div class="brand text-left pl-3">
				<a href="{{ route($targetRouteName,['product' => $product->id ]) }}" class="text-decoration-none" style="color:var(--red);">{{ $product->brand->name }}</a>
			</div>

			<div class="name">
				<a href="{{ route($targetRouteName,['product' => $product->id ]) }}" class="text-decoration-none" style="color:var(--red);">{{ $product->name_cn }}</a>
			</div>

			<div class="price">
				<a href="{{ route('products.show',['product' => $product ]) }}" style="color:var(--red);">
						{{ ($product->price)?"\u{00a5}".$product->price:'SOLD OUT' }}
				</a>
			</div>

		</div>
	</div>

</div>
@endforeach

@endsection


@section('right-aside')
<div class="form-group pr-3 pt-2 collapse show" id="sort-checkboxes">
	@foreach(App\Sortmethod::all() as $value)
	<div class="form-check">
		<input class="form-check-input" type="radio" name="sort" value="{{ $value->name }}" id="{{ $value->name }}" onchange="submit()" {{ (old('sort')==$value->name) || (!old('sort') && $value->name=='default')?' checked':'' }}>
		<label class="form-check-label" for="{{ $value->name }}">{{ $value->name_cn }}</label>
	</div>
	@endforeach
</div>
@endsection
