@extends('layouts.index')

@section('title','所有商品 - TheShowroom')

@section('left-aside')
@foreach([
"category" => App\Category::all(),
"color" => App\Color::all(),
"season" => App\Season::all(),
"brand" => App\Brand::all(),
] as $key => $values)
<div class="pl-2 pt-1">
	<div class="">
		<a class="" data-toggle="collapse" href="#{{$key}}-group" style="color:var(--blue);">{{$key}}</a>
	</div>

	<div class="form-group pl-2 pt-1 mb-0 collapse show" id="{{$key}}-group">
		@foreach($values as $value)
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="{{$key}}[]" value="{{$value->id}}" id="{{$key}}-{{$value->id}}" onchange="submit()" {{ (old("{$key}") && in_array("{$value->id}",old("{$key}")))?' checked':'' }}>
			<label class="form-check-label" for="{{$key}}-{{$value->id}}">{{$value->name_cn??$value->name}}</label>
		</div>
		@endforeach
	</div>

</div>
@endforeach

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
				<a href="{{ route($targetRouteName,['product' => $product->id ]) }}" style="color:var(--red);">{{ $product->displayPrice() }}</a>
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
