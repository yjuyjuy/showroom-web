@extends('layouts.app')

@section('title',$product->displayName().' - TheShowroom')

@section('content')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>

	<div class="content-container d-flex flex-column">
		@include('farfetch.show.properties')
		@include('farfetch.show.customer')
	</div>
</div>
@endsection
