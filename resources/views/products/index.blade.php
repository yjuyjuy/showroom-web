@extends('layouts.app')
@section('title','所有商品 - TheShowroom')
@section('content')
<div class="container">
	<form action="{{ url()->current() }}" class="row">

		<div class="col order-md-2">
				@include('products.index.products')
		</div>

		<div class="col-md-auto order-md-1">
			@include('products.index.sort')
			<div class="w-100"></div>
			@include('products.index.filters')
		</div>

	</form>
</div>
@endsection
