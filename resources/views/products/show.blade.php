@extends('layouts.app')

@section('title', $product->displayName())

@section('content')
<div class="container">
	<div>
		@include('products.show.images')
	</div>

	<div class="container__content d-flex flex-column">

		@include('products.show.properties')

		@include('products.show.customer')

		@if($user->is_reseller)
			@include('products.show.reseller')
		@endif

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
