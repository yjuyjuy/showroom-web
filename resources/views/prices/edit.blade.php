@extends('layouts.app')

@section('title','修改报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6 order-2 order-md-1">
			@include('products.show.images')
		</div>
		<div class="col-md-5 order-1 order-md-2">
			<form action="{{route('prices.update',['price' => $price])}}" method="post" class="row mb-3" id="update-form">
				@csrf
				@method('PATCH')
				<prices-editor v-bind:input='@json(array_values($price->data))'></prices-editor>
			</form>
			<div class="row justify-content-end mb-3" >
				<div class="col-auto">
					<form action="{{route('prices.destroy',['price' => $price])}}" method="post" class="row mb-3" id="delete-form">
						@csrf
						@method('DELETE')
					</form>
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-primary mr-2">Back</a>
					<button type="submit" class="btn btn-primary mr-2" form="update-form">
						Submit
					</button>
					<button type="submit" class="btn btn-danger" form="delete-form">
						Delete All
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
