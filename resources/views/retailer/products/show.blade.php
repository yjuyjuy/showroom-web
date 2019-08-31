@extends('layouts.app')

@section('title', $product->displayName().' - '.$retailer->name)

@section('content')
@include('retailer.banner')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>

	<div class="content-container d-flex flex-column">
		@include('products.show.properties')
		@include('products.show.customer')
		@include('products.show.buttons')
		@if(auth()->user() && auth()->user()->isSuperAdmin())
			@include('products.show.admin')
		@elseif(auth()->user() && ($vendor = auth()->user()->vendor))
			@include('products.show.vendor')
		@endif
	</div>
</div>
@include('layouts.back_fab')
@endsection
