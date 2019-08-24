@extends('layouts.app')

@section('title',$product->displayName())

@section('content')
<div class="images-content-container">
	<div class="images-container">
		@include('products.show.images')
	</div>

	<div class="content-container d-flex flex-column">
		@include('products.show.properties')
		@include('products.show.customer')
		@auth
			@if($user->is_reseller)
				@include('products.show.reseller')
			@endif
			@if($user->isSuperAdmin())
				@include('products.show.admin')
			@else
				@if($vendor = $user->vendor)
					@include('products.show.vendor')
				@endif
			@endif
		@endauth
	</div>
</div>
@endsection
