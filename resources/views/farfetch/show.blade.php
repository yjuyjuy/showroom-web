@extends('layouts.app')

@section('title',$product->displayName().' - TheShowroom')

@section('content')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>

	<div class="content-container d-flex flex-column">
		@include('products.show.properties')
		@include('products.show.customer')
		@auth
			@if(Auth::user()->isSuperAdmin())
				@include('products.show.admin')
			@elseif($vendor = Auth::user()->vendor)
				@include('products.show.vendor')
			@endif
		@endauth
	</div>
</div>
@endsection
