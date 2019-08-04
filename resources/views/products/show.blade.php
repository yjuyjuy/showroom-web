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
		@include('products.show.links')
		@auth
			<?php $user = Auth::user(); ?>
			@if($user->isReseller())
				@include('products.show.reseller')
			@endif
			@if($user->isSuperAdmin())
				@include('products.show.admin')
			@elseif($user->isReseller())
				@include('products.show.vendor')
			@endif
		@endauth
	</div>
</div>
@endsection
