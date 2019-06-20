@extends('layouts.app')

@section('title','添加报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6 order-2 order-md-1">
			@include('products.show.images')
		</div>
		<div class="col-md-5 order-1 order-md-2">
			<form action="index.html" method="post" class="row mb-3">
				@csrf
				<prices-editor v-bind:input="[]"></prices-editor>
			</form>
			<div class="row justify-content-end mb-3" >
				<div class="col-auto">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-primary mr-2">Back</a>
					<button type="submit" class="btn btn-primary">
						Submit
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
