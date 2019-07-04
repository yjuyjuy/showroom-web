@extends('layouts.app')
@section('title','所有商品 - TheShowroom')
@section('content')
<div class="container">
	<form action="{{ url()->current() }}" class="row">
		<div class="col-auto d-none d-md-block">
			<div class="mx-auto">
				@include('products.index.filters')
			</div>
		</div>
		<div class="col">
				@include('products.index.products')
		</div>
		<div class="col-auto d-none d-md-block">
			<div class="mx-auto">
				@include('products.index.sort')
			</div>
		</div>
	</form>
</div>
@endsection
