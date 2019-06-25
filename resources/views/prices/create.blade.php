@extends('layouts.app')

@section('title','添加报价-'.$product->displayName().'-TheShowroom')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			@include('products.show.images')
		</div>
		<div class="col-md-6">
			<form action="{{route('prices.store',['product'=>$product])}}" method="post" class="" id="create-form">
				@csrf
				@if(auth()->user()->isSuperAdmin())
				<input type="hidden" name="vendor" value="{{$vendor->id}}">
				<div class="row mx-2 my-4">
					<div class="col fon-weight-bold">
						{{$vendor->name.' - '.$vendor->city}}
					</div>
				</div>
				@endif
				<prices-editor v-bind:input="[]"></prices-editor>
			</form>
			<div class="container-fluid">
				<div class="row no-gutters">
					<a href="{{route('products.show',['product' => $product])}}" class="btn btn-outline-secondary">Back</a>
					<button type="submit" class="btn btn-outline-primary ml-2" form="create-form">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
