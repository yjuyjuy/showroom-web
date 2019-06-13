@extends('layouts.edit')

@section('title','修改价格 - '.$price->product->displayName(1).' - TheShowroom')

@section('left-aside')
<div class="row">
	<div class="col-12">
		<img src="/storage/images/{{$product->images()->first()->filename}}" alt="" class="w-100">
	</div>
</div>
@include('products.properties')
@endsection


@section('right-aside')
<price-input-component></price-input-component>
@endsection
