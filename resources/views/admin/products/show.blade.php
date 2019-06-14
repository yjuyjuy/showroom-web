@extends('products.show')

@section('right-aside')
<div class="py-4 my-2 mx-auto col-12 col-md-10 border">
	<div class="">调货</div>
	@foreach($product->getSizePrice('resell') as $size => $price)
	<div class="">{{$size}} - {{$price}}</div>
	@endforeach
</div>
<div class="py-4 my-2 mx-auto col-12 col-md-10 border">
	<div class="">零售</div>
	@foreach($product->getSizePrice('retail') as $size => $price)
	<div class="">{{$size}} - {{$price}}</div>
	@endforeach
</div>

@foreach($product->prices as $price)
<div class="py-4 my-2 mx-auto col-12 col-md-10 border">
	<div class="mt-n2 mb-2">{{$price->vendor->name}} - {{$price->vendor->city}}</div>
	<div class="row">
		<div class="col text-center">
			尺码
		</div>
		<div class="col text-center">
			成本
		</div>
		<div class="col text-center">
			调货
		</div>
		<div class="col text-center">
			零售
		</div>
	</div>
	@foreach($price->data as $row)
	<div class="row">
		<div class="col px-2 py-1 text-center">
			<span>{{$row['size']}}</span>
		</div>
		<div class="col px-2 py-1 text-center">
			<span>{{$row['cost']}}</span>
		</div>
		<div class="col px-2 py-1 text-center">
			<span>{{$row['resell']}}</span>
		</div>
		<div class="col px-2 py-1 text-center">
			<span>{{$row['retail']}}</span>
		</div>
	</div>
	@endforeach
	<div class="row">
		<div class="col px-2 py-1 text-center">
			<a href=""></a>
		</div>
		<div class="col px-2 py-1 text-center">
			<a href=""></a>
		</div>
		<div class="col px-2 py-1 text-center">
			<a href="">edit</a>
		</div>
		<div class="col px-2 py-1 text-center">
			<a href="">delete</a>
		</div>

	</div>
</div>
@endforeach

@endsection
