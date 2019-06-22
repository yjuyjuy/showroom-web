@extends('layouts.app')

@section('title','修改报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6 order-2 order-md-1">
			@include('products.show.images')
		</div>
		<div class="col-md-6 col-lg-5 order-1 order-md-2">
			<form action="{{route('prices.update',['price' => $price])}}" method="post" class="row mb-3" id="update-form">
				@csrf
				@method('PATCH')
				@if(auth()->user()->isSuperAdmin())
				<div class="col-12 text-center h3">{{$price->vendor->name.' - '.$price->vendor->city}}</div>
				@endif
				<prices-editor v-bind:input='@json(array_values($price->data))'></prices-editor>
			</form>
			<div class="row justify-content-end mb-3">
				<div class="col-auto">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-primary mr-2">Back</a>
					<button type="submit" class="btn btn-primary mr-2" form="update-form">Submit</button>
					<button type="button" class="btn btn-danger" @click="deletePrice({{$price->id}})">Delete All</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
