@extends('layouts.show')

@section('title',$product->brand->name.' '.$product->season->name.' '.$product->name_cn.' - TheShowroom')

@section('left-aside')
<div class="d-flex flex-column text-center justify-content-center vh-100">

	<div class="align-self-center mt-n5">
		<a href="{{ route('products.index') }}?brand[]={{$product->brand->id}}">{{ $product->brand->full_name }}</a>
	</div>

	<div class="align-self-center">
		<span><a href="{{ route('products.index') }}?season[]={{$product->season->id}}">{{ $product->season->name }}</a> {{ $product->name_cn }}</span>
	</div>

	<div class="align-self-center">
		<span>{{ $product->id }}</span>
	</div>

	<div class="align-self-center">
		<a href="{{ route('products.edit',['product' => $product->id ]) }}">edit</a>
	</div>

</div>
@endsection


@section('center')
<div class="row">
	@foreach($product->images as $image)
	<div class="col-6 pb-4">
		<img class="w-100" src="/storage/images/{{ $image->filename }}">
	</div>
	@endforeach
</div>
@endsection


@section('right-aside')
<div class="align-self-center">
	@if(!empty($sizes))
	@foreach($sizes as $size => $price)
	<div class="">{{ $size }} - &yen;{{$price}}</div>
	@endforeach
	@else
	<div class="">Currently not available</div>
	@endif
<div>
@endsection
