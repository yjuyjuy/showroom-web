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

		@include('products.show.buttons')

		@if($user->is_admin)
			@include('products.show.admin')

		@elseif($user->vendor)
			@include('products.show.vendor')

		@else
			@include('products.show.reseller')

		@endif
	</div>
</div>
@include('layouts.back_fab')
@endsection
