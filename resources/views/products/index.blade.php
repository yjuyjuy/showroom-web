@extends('layouts.app')
@section('title','所有商品 - TheShowroom')
@section('content')
<div class="container-fluid">
	<form action="{{ url()->current() }}" class="row">
		<div class="col-2 d-flex">
			<div class="ml-auto">
				@include('products.index.filters')
			</div>
		</div>
		<div class="col-8">
			<div class="row">
				@include('products.index.products')
			</div>
		</div>
		<div class="col-2">
			<div class="mr-auto">
				@include('products.index.sort')
			</div>
		</div>
	</form>
</div>
@endsection
