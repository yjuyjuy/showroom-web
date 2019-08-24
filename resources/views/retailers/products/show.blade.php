@extends('layouts.app')

@section('title',$product->displayName().' - '.$retailer->name)

@section('content')
@include('retailers.banner')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>

	<div class="content-container d-flex flex-column">
		@include('products.show.properties')
		@include('products.show.customer')
	</div>
</div>
@endsection
