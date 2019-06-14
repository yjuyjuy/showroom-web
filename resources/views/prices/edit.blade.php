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
<div class="py-4 my-4 mx-auto col-10 col-md-8 border">
	<form action="{{route('prices.update',['price'=>$price,])}}" method="post">
	@csrf
	@method('PATCH')
	<div class="row mb-2">
	  <div class="col">
			<span>尺码</span>
	  </div>
		<div class="col">
			<span>成本</span>
	  </div>
		<div class="col">
			<span>调货价</span>
	  </div>
		<div class="col">
			<span>零售价</span>
	  </div>
	</div>
	@foreach($price->data as $row)
	<price-input-component current_values='{{ json_encode($row) }}'></price-input-component>
	@endforeach
	<button type="submit" class="btn btn-primary text-center">Update</button>
	</form>
</div>
@endsection
