@extends('layouts.app')

@section('title',$product->displayName().' - '.$vendor->name)

@section('content')
@include('vendor.banner')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>

	<div class="content-container d-flex flex-column">
		@include('products.show.properties')
		@include('products.show.reseller')
		@include('products.show.buttons')
	</div>
</div>
@include('layouts.back_fab')
@endsection
