@extends('layouts.app')
@section('title','所有商品 - TheShowroom')
@section('content')
<div class="">
	<form action="{{ url()->current() }}" class=" ">
		<div class=" ">
			<div class="mx-auto">
				@include('products.index.filters')
			</div>
		</div>
		<div class="col">
				@include('products.index.products')
		</div>
		<div class=" ">
			<div class="mx-auto">
				@include('products.index.sort')
			</div>
		</div>
	</form>
</div>
@endsection
