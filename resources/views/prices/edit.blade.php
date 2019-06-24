@extends('layouts.app')

@section('title','修改报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="">
	<div class="">
		<div class="">
			@include('products.show.images')
		</div>
		<div class="">
			<form action="{{route('prices.update',['price' => $price])}}" method="post" class="" id="update-form">
				@csrf
				@method('PATCH')
				@if(auth()->user()->isSuperAdmin())
				<div class="  h3">{{$price->vendor->name.' - '.$price->vendor->city}}</div>
				@endif
				<prices-editor v-bind:input='@json(array_values($price->data))'></prices-editor>
			</form>
			<div class="">
				<div class="">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-primary ">Back</a>
					<button type="submit" class="btn btn-primary " form="update-form">Submit</button>
					<button type="button" class="btn btn-danger" @click="deletePrice({{$price->id}})">Delete All</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
