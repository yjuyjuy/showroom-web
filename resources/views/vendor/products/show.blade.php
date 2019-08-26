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
		@if($user->isSuperAdmin())
			@include('products.show.admin')
		@else
			@if($vendor = $user->vendor)
				@include('products.show.vendor')
			@endif
		@endif
	</div>
</div>
@include('layouts.back_fab')
@endsection
