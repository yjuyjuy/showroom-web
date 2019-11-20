@extends('layouts.app')

@section('title', $product->displayName().' - '.$vendor->name)

@section('content')
@include('vendor.banner')
<div class="container">
	<div class="">
		@include('products.show.images')
	</div>

	<div class="container__content d-flex flex-column">

		@include('products.show.properties')

		@include('products.show.reseller')

		@include('products.show.buttons')

		@if($user->isSuperAdmin())
			@include('products.show.admin')

		@elseif($vendor = $user->vendor)
			@include('products.show.vendor')
		@endif
	</div>
</div>
@include('layouts.back_fab')
@endsection
