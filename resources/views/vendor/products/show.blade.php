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

		@auth
			@if($user->isSuperAdmin())
				@include('products.show.admin')

			@elseif($user->vendor)
				@include('products.show.vendor')

			@elseif($user->is_reseller)
				@include('products.show.reseller')

			@endif
		@endauth
	</div>
</div>
@include('layouts.back_fab')
@endsection
