@extends('layouts.app')

@section('title','添加报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6 order-2 order-md-1">
			@include('products.show.images')
		</div>
		<div class="col-md-6 col-lg-5s order-1 order-md-2">
			<form action="{{route('prices.store',['product'=>$product])}}" method="post" class="row mb-3" id="create-form">
				@csrf
				@if(auth()->user()->isSuperAdmin())
				<input type="hidden" name="vendor" value="{{$vendor->id}}">
				<div class="col-12 text-center h3">{{$vendor->name.' - '.$vendor->city}}</div>
				@endif
				<prices-editor v-bind:input="[]"></prices-editor>
			</form>
			<div class="row justify-content-end mb-3">
				<div class="col-auto">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-primary mr-2">Back</a>
					<button type="submit" class="btn btn-primary" form="create-form">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
